window.addEventListener('load', () => {
  let loadFlag = false
  let searchReady = false
  let escBound = false
  const openSearch = () => {
    const searchDialog = document.querySelector('#local-search .search-dialog')
    const searchInput = document.querySelector('#local-search-input input')
    const searchMask = document.getElementById('search-mask')
    if (!searchDialog || !searchInput || !searchMask) return
    const bodyStyle = document.body.style
    bodyStyle.width = '100%'
    bodyStyle.overflow = 'hidden'
    btf.animateIn(searchMask, 'to_show 0.5s')
    btf.animateIn(searchDialog, 'titleScale 0.5s')
    setTimeout(() => { searchInput.focus() }, 100)
    if (!loadFlag) {
      search(GLOBAL_CONFIG.localSearch.path)
      loadFlag = true
    }
  }

  const closeSearch = () => {
    const searchDialog = document.querySelector('#local-search .search-dialog')
    const searchMask = document.getElementById('search-mask')
    if (!searchDialog || !searchMask) return
    const bodyStyle = document.body.style
    bodyStyle.width = ''
    bodyStyle.overflow = ''
    btf.animateOut(searchDialog, 'search_close .5s')
    btf.animateOut(searchMask, 'to_hide 0.5s')
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
      const searchDialog = document.querySelector('#local-search .search-dialog')
      if (!searchDialog) return
      if (event.code === 'Escape' && getComputedStyle(searchDialog).display === 'block') {
        closeSearch()
      }
    })
  }

  searchClickFn()
  bindEscKey()

  // pjax
  window.addEventListener('pjax:complete', function () {
    const searchDialog = document.querySelector('#local-search .search-dialog')
    searchDialog && getComputedStyle(searchDialog).display === 'block' && closeSearch()
    searchClickFn()
  })

  async function search (path) {
    if (searchReady) return
    let datas = []
    const typeF = path.split('.')[1]
    const response = await fetch(GLOBAL_CONFIG.root + path)
    if (typeF === 'json') {
      datas = await response.json()
    } else if (typeF === 'xml') {
      const res = await response.text()
      const t = await new window.DOMParser().parseFromString(res, 'text/xml')
      const a = await t
      datas = [...a.querySelectorAll('entry')].map(function (item) {
        return {
          title: item.querySelector('title').textContent,
          content: item.querySelector('content').textContent,
          url: item.querySelector('url').textContent
        }
      })
    }
    if (response.ok) {
      const $loadDataItem = document.getElementById('loading-database')
      $loadDataItem.nextElementSibling.style.display = 'block'
      $loadDataItem.remove()
    }

    const $input = document.querySelector('#local-search-input input')
    const $resultContent = document.getElementById('local-search-results')
    const $loadingStatus = document.getElementById('loading-status')
    if (!$input || !$resultContent || !$loadingStatus) return
    searchReady = true
    $input.addEventListener('input', function () {
      const keywords = this.value.trim().toLowerCase().split(/[\s]+/)
      if (keywords[0] !== '') $loadingStatus.innerHTML = '<i class="fas fa-spinner fa-pulse"></i>'

      $resultContent.innerHTML = ''
      let str = '<div class="search-result-list">'
      if (this.value.trim().length <= 0) return
      let count = 0
      // perform local searching
      datas.forEach(function (data) {
        let isMatch = true
        if (!data.title || data.title.trim() === '') {
          data.title = ''
        }
        let dataTitle = data.title.trim().toLowerCase()
        const dataContent = data.content ? data.content.trim().replace(/<[^>]+>/g, '').toLowerCase() : ''
        const dataUrl = data.url.startsWith('/') ? data.url : GLOBAL_CONFIG.root + data.url
        let indexTitle = -1
        let indexContent = -1
        let firstOccur = -1
        // only match artiles with not empty titles and contents
        if (dataTitle !== '' || dataContent !== '') {
          keywords.forEach(function (keyword, i) {
            indexTitle = dataTitle.indexOf(keyword)
            indexContent = dataContent.indexOf(keyword)
            if (indexTitle < 0 && indexContent < 0) {
              isMatch = false
            } else {
              if (indexContent < 0) {
                indexContent = 0
              }
              if (i === 0) {
                firstOccur = indexContent
              }
            }
          })
        } else {
          isMatch = false
        }

        // show search results
        if (isMatch) {
          const content = data.content.trim().replace(/<[^>]+>/g, '')
          if (firstOccur >= 0) {
            // cut out 130 characters
            // let start = firstOccur - 30 < 0 ? 0 : firstOccur - 30
            // let end = firstOccur + 50 > content.length ? content.length : firstOccur + 50
            let start = firstOccur - 30
            let end = firstOccur + 100

            if (start < 0) {
              start = 0
            }

            if (start === 0) {
              end = 100
            }

            if (end > content.length) {
              end = content.length
            }

            let matchContent = content.substring(start, end)

            // highlight all keywords
            keywords.forEach(function (keyword) {
              const regS = new RegExp(keyword, 'gi')
              matchContent = matchContent.replace(regS, '<span class="search-keyword">' + keyword + '</span>')
              dataTitle = dataTitle.replace(regS, '<span class="search-keyword">' + keyword + '</span>')
            })

            str += '<div class="local-search__hit-item"><a href="' + dataUrl + '" class="search-result-title">' + dataTitle + '</a>'
            count += 1

            if (dataContent !== '') {
              str += '<p class="search-result">' + matchContent + '...</p>'
            }
          }
          str += '</div>'
        }
      })
      if (count === 0) {
        str += '<div id="local-search__hits-empty">' + GLOBAL_CONFIG.localSearch.languages.hits_empty.replace(/\$\{query}/, this.value.trim()) +
          '</div>'
      }
      str += '</div>'
      $resultContent.innerHTML = str
      if (keywords[0] !== '') $loadingStatus.innerHTML = ''
      window.pjax && window.pjax.refresh($resultContent)
    })
  }
})
