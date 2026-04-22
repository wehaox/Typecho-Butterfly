window.addEventListener('load', () => {
  let escBound = false
  let debounceTimer = null
  let currentController = null
  let requestSerial = 0
  let searchOpen = false

  const getSearchElements = () => ({
    searchDialog: document.querySelector('#local-search .search-dialog'),
    searchInput: document.querySelector('#local-search-input input'),
    searchMask: document.getElementById('search-mask'),
    resultContent: document.getElementById('local-search-results'),
    loadingStatus: document.getElementById('loading-status')
  })

  const getSearchPath = () => {
    if (!GLOBAL_CONFIG.localSearch || !GLOBAL_CONFIG.localSearch.path) return ''
    return GLOBAL_CONFIG.localSearch.path
  }

  const getSearchLanguages = () => {
    if (!GLOBAL_CONFIG.localSearch || !GLOBAL_CONFIG.localSearch.languages) {
      return {
        hits_empty: '未找到与 “${query}” 相关内容',
        query_too_short: '请输入至少 2 个字的关键词',
        search_error: '搜索失败，请稍后重试'
      }
    }

    return GLOBAL_CONFIG.localSearch.languages
  }

  const escapeHtml = str => String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;')

  const escapeRegExp = str => String(str).replace(/[.*+?^${}()|[\]\\]/g, '\\$&')

  const parseKeywords = query => query.trim().toLowerCase().split(/[\s,，]+/).map(item => item.trim()).filter(Boolean)

  const getKeywordLength = keyword => Array.from(String(keyword || '')).length

  const hasInvalidKeywords = query => parseKeywords(query).some(item => getKeywordLength(item) < 2)

  const getValidKeywords = query => parseKeywords(query).filter(item => getKeywordLength(item) >= 2).slice(0, 3)

  const getHitsEmptyText = query => {
    const emptyText = getSearchLanguages().hits_empty || '未找到与 “${query}” 相关内容'

    return emptyText.replace(/\$\{query}/, escapeHtml(query))
  }

  const getTooShortText = () => getSearchLanguages().query_too_short || '请输入至少 2 个字的关键词'

  const getSearchErrorText = () => getSearchLanguages().search_error || '搜索失败，请稍后重试'

  const buildHighlightRegExp = keywords => {
    const mergedKeywords = [...new Set(keywords.filter(Boolean))].sort((a, b) => b.length - a.length)
    if (!mergedKeywords.length) return null

    const pattern = mergedKeywords.map(keyword => escapeRegExp(keyword)).join('|')
    return pattern ? new RegExp(pattern, 'gi') : null
  }

  const highlightText = (text, keywords) => {
    const escapedText = escapeHtml(text || '')
    const highlightRegExp = buildHighlightRegExp(keywords)

    if (!highlightRegExp) {
      return escapedText
    }

    return escapedText.replace(highlightRegExp, match => '<span class="search-keyword">' + match + '</span>')
  }

  const renderSearchResults = (items, query) => {
    const { resultContent } = getSearchElements()
    if (!resultContent) return

    const keywords = getValidKeywords(query)
    let str = '<div class="search-result-list">'

    if (!items.length) {
      str += '<div id="local-search__hits-empty">' + getHitsEmptyText(query) + '</div>'
    } else {
      items.forEach(item => {
        const title = highlightText(item.title || '未命名', keywords)
        const content = highlightText(item.content || '', keywords)
        const dataUrl = escapeHtml(item.url || '#')

        str += '<div class="local-search__hit-item"><a href="' + dataUrl + '" class="search-result-title">' + title + '</a>'
        if (content !== '') {
          str += '<p class="search-result">' + content + '</p>'
        }
        str += '</div>'
      })
    }

    str += '</div>'
    resultContent.innerHTML = str
    window.pjax && typeof window.pjax.refresh === 'function' && window.pjax.refresh(resultContent)
  }

  const renderSearchMessage = message => {
    const { resultContent } = getSearchElements()
    if (!resultContent) return
    resultContent.innerHTML = '<div class="search-result-list"><div id="local-search__hits-empty">' + escapeHtml(message) + '</div></div>'
  }

  const renderSearchError = message => {
    renderSearchMessage(message || getSearchErrorText())
  }

  const clearLoadingStatus = () => {
    const { loadingStatus } = getSearchElements()
    if (loadingStatus) loadingStatus.innerHTML = ''
  }

  const resetBodyScrollLock = () => {
    const bodyStyle = document.body.style
    bodyStyle.width = ''
    bodyStyle.overflow = ''
  }

  const clearPendingSearch = () => {
    requestSerial += 1

    if (debounceTimer) {
      clearTimeout(debounceTimer)
      debounceTimer = null
    }

    if (currentController) {
      currentController.abort()
      currentController = null
    }

    clearLoadingStatus()
  }

  const buildRequestUrl = (path, query) => {
    const requestUrl = new URL(path, window.location.href)
    requestUrl.searchParams.set('keywords', query)
    return requestUrl.toString()
  }

  const requestSearch = (path, query) => {
    const { loadingStatus, resultContent } = getSearchElements()
    if (!loadingStatus || !resultContent) return
    const trimmedQuery = query.trim()
    const validKeywords = getValidKeywords(trimmedQuery)

    clearTimeout(debounceTimer)

    if (!trimmedQuery) {
      clearPendingSearch()
      resultContent.innerHTML = ''
      return
    }

    if (hasInvalidKeywords(trimmedQuery) || !validKeywords.length) {
      clearPendingSearch()
      renderSearchMessage(getTooShortText())
      return
    }

    loadingStatus.innerHTML = '<i class="fas fa-spinner fa-pulse"></i>'
    debounceTimer = setTimeout(async () => {
      const currentRequestId = ++requestSerial

      if (currentController) {
        currentController.abort()
      }

      currentController = new window.AbortController()

      try {
        const response = await fetch(buildRequestUrl(path, validKeywords.join(' ')), {
          signal: currentController.signal,
          credentials: 'same-origin'
        })
        let result = null

        try {
          result = await response.json()
        } catch (jsonError) {
          throw new Error(getSearchErrorText())
        }

        if (currentRequestId !== requestSerial) return
        if (!response.ok || result.status !== 'success') {
          throw new Error(result.message || getSearchErrorText())
        }

        const items = result.data && Array.isArray(result.data.items) ? result.data.items : []
        renderSearchResults(items, trimmedQuery)
      } catch (error) {
        if (error.name === 'AbortError' || currentRequestId !== requestSerial) return
        renderSearchError(error.message)
      } finally {
        if (currentRequestId === requestSerial) {
          clearLoadingStatus()
        }
      }
    }, 300)
  }

  const initSearch = path => {
    const { searchInput } = getSearchElements()
    if (!searchInput || !path) return

    if (searchInput.dataset.searchApiInit === 'true') {
      return
    }

    searchInput.dataset.searchApiInit = 'true'
    searchInput.addEventListener('input', function () {
      requestSearch(path, this.value)
    })
  }

  const openSearch = () => {
    const { searchDialog, searchInput, searchMask } = getSearchElements()
    if (!searchDialog || !searchInput || !searchMask) return

    const searchPath = getSearchPath()
    initSearch(searchPath)

    searchOpen = true

    const bodyStyle = document.body.style
    bodyStyle.width = '100%'
    bodyStyle.overflow = 'hidden'
    btf.animateIn(searchMask, 'to_show 0.5s')
    btf.animateIn(searchDialog, 'titleScale 0.5s')
    setTimeout(() => { searchInput.focus() }, 100)

    if (searchInput.value.trim() !== '') {
      searchInput.dispatchEvent(new Event('input'))
    }
  }

  const closeSearch = () => {
    const { searchDialog, searchMask } = getSearchElements()
    if (!searchDialog || !searchMask) return

    searchOpen = false
    clearPendingSearch()

    resetBodyScrollLock()
    btf.animateOut(searchDialog, 'search_close .5s')
    btf.animateOut(searchMask, 'to_hide 0.5s')
  }

  const bindResultClick = () => {
    const { resultContent } = getSearchElements()
    if (!resultContent || resultContent.dataset.searchResultInit === 'true') return

    resultContent.dataset.searchResultInit = 'true'
    resultContent.addEventListener('click', function (event) {
      const target = event.target.closest('.search-result-title')
      if (!target) return
      closeSearch()
    })
  }

  // click function
  const searchClickFn = () => {
    const searchButton = document.querySelector('#search-button > .search')
    const searchMask = document.getElementById('search-mask')
    const closeButton = document.querySelector('#local-search .search-close-button')
    if (searchButton && searchButton.dataset.searchInit !== 'true') {
      searchButton.dataset.searchInit = 'true'
      searchButton.addEventListener('click', openSearch)
    }
    if (searchMask && searchMask.dataset.searchMaskInit !== 'true') {
      searchMask.dataset.searchMaskInit = 'true'
      searchMask.addEventListener('click', closeSearch)
    }
    if (closeButton && closeButton.dataset.searchCloseInit !== 'true') {
      closeButton.dataset.searchCloseInit = 'true'
      closeButton.addEventListener('click', closeSearch)
    }
  }

  const bindEscKey = () => {
    if (escBound) return
    escBound = true
    document.addEventListener('keydown', function (event) {
      const { searchDialog } = getSearchElements()
      if (!searchDialog) return
      if (event.code === 'Escape' && getComputedStyle(searchDialog).display === 'block') {
        closeSearch()
      }
    })
  }

  searchClickFn()
  bindResultClick()
  bindEscKey()

  document.addEventListener('pjax:send', function () {
    if (!searchOpen) {
      resetBodyScrollLock()
      return
    }

    closeSearch()
  })

  // pjax
  document.addEventListener('pjax:complete', function () {
    const { searchDialog } = getSearchElements()
    searchDialog && getComputedStyle(searchDialog).display === 'block' && closeSearch()
    searchClickFn()
    bindResultClick()
  })
})
