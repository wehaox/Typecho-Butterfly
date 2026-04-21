;(function (window) {
  const ExternalLinkConfirm = {
    init: ({ enableNewTab = false, enableConfirm = false } = {}) => {
      if (!enableNewTab && !enableConfirm) {
        return
      }

      const currentHost = window.location.hostname
      const modalId = 'external-link-confirm-modal'

      const getExternalUrl = url => {
        if (!url) {
          return null
        }

        const raw = String(url).trim()
        if (!raw || raw.charAt(0) === '#') {
          return null
        }

        const lowerUrl = raw.toLowerCase()
        if (lowerUrl.indexOf('javascript:') === 0 || lowerUrl.indexOf('mailto:') === 0 || lowerUrl.indexOf('tel:') === 0) {
          return null
        }

        try {
          const parsed = new URL(raw, window.location.href)
          if (parsed.protocol !== 'http:' && parsed.protocol !== 'https:') {
            return null
          }
          if (parsed.hostname === currentHost) {
            return null
          }
          return parsed
        } catch (e) {
          return null
        }
      }

      const closeExternalModal = () => {
        const mask = document.getElementById(modalId)
        if (!mask) {
          return
        }
        mask.classList.remove('is-active')
        document.body.classList.remove('external-link-modal-open')
        window.__externalLinkModalPendingAction = null
      }

      const ensureExternalModal = () => {
        let modalMask = document.getElementById(modalId)
        if (modalMask) {
          return modalMask
        }

        modalMask = document.createElement('div')
        modalMask.id = modalId
        modalMask.className = 'external-link-modal-mask'
        modalMask.innerHTML = `
          <div class="external-link-modal" role="dialog" aria-modal="true" aria-labelledby="external-link-modal-title">
            <div class="external-link-modal__header">
              <h3 class="external-link-modal__title" id="external-link-modal-title"><i class="fas fa-external-link-alt"></i>站外链接访问确认</h3>
              <button type="button" class="external-link-modal__close" aria-label="关闭"><i class="fas fa-times"></i></button>
            </div>
            <p class="external-link-modal__desc">即将离开本站，目标链接如下：</p>
            <div class="external-link-modal__url-box">
              <p class="external-link-modal__url" id="external-link-modal-url"></p>
              <button type="button" class="external-link-modal__copy" id="external-link-modal-copy" aria-label="复制链接" title="复制链接"><i class="fas fa-copy"></i></button>
            </div>
            <div class="external-link-modal__actions">
              <button type="button" class="external-link-modal__btn external-link-modal__btn--cancel" id="external-link-modal-cancel">取消前往</button>
              <button type="button" class="external-link-modal__btn external-link-modal__btn--confirm" id="external-link-modal-confirm">前往链接</button>
            </div>
          </div>
        `

        document.body.appendChild(modalMask)

        const closeBtn = modalMask.querySelector('.external-link-modal__close')
        const cancelBtn = modalMask.querySelector('#external-link-modal-cancel')
        const confirmBtn = modalMask.querySelector('#external-link-modal-confirm')
        const copyBtn = modalMask.querySelector('#external-link-modal-copy')
        const urlNode = modalMask.querySelector('#external-link-modal-url')

        closeBtn.addEventListener('click', closeExternalModal)
        cancelBtn.addEventListener('click', closeExternalModal)

        modalMask.addEventListener('click', event => {
          if (event.target === modalMask) {
            closeExternalModal()
          }
        })

        confirmBtn.addEventListener('click', () => {
          const action = window.__externalLinkModalPendingAction
          closeExternalModal()
          if (typeof action === 'function') {
            action()
          }
        })

        copyBtn.addEventListener('click', () => {
          const copyUrl = urlNode ? (urlNode.textContent || '') : ''
          if (!copyUrl) {
            return
          }

          const done = () => {
            copyBtn.innerHTML = '<i class="fas fa-check"></i>'
            copyBtn.classList.add('is-success')
            setTimeout(() => {
              copyBtn.innerHTML = '<i class="fas fa-copy"></i>'
              copyBtn.classList.remove('is-success')
            }, 1200)
          }

          if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(copyUrl).then(done).catch(() => {
              const textArea = document.createElement('textarea')
              textArea.value = copyUrl
              document.body.appendChild(textArea)
              textArea.select()
              document.execCommand('copy')
              document.body.removeChild(textArea)
              done()
            })
            return
          }

          const textArea = document.createElement('textarea')
          textArea.value = copyUrl
          document.body.appendChild(textArea)
          textArea.select()
          document.execCommand('copy')
          document.body.removeChild(textArea)
          done()
        })

        if (!window.__externalLinkModalEscBound) {
          window.__externalLinkModalEscBound = true
          document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
              closeExternalModal()
            }
          })
        }

        return modalMask
      }

      const openExternalModal = (url, onConfirm) => {
        const modalMask = ensureExternalModal()
        if (!modalMask) {
          return
        }
        const urlNode = modalMask.querySelector('#external-link-modal-url')
        const copyBtn = modalMask.querySelector('#external-link-modal-copy')
        window.__externalLinkModalPendingAction = onConfirm
        if (urlNode) {
          urlNode.textContent = url
        }
        if (copyBtn) {
          copyBtn.innerHTML = '<i class="fas fa-copy"></i>'
          copyBtn.classList.remove('is-success')
        }
        modalMask.classList.add('is-active')
        document.body.classList.add('external-link-modal-open')
      }

      const bindExternalLinks = () => {
        const links = document.querySelectorAll('a[href]')
        for (let i = 0; i < links.length; i++) {
          const link = links[i]
          const href = link.getAttribute('href')
          const externalUrl = getExternalUrl(href)

          if (!externalUrl) {
            continue
          }

          if (enableNewTab) {
            link.setAttribute('target', '_blank')
            link.setAttribute('rel', 'noopener noreferrer')
          }

          if (!enableConfirm || link.dataset.externalConfirmBound === '1') {
            continue
          }

          link.dataset.externalConfirmBound = '1'
          link.addEventListener('click', function (event) {
            if (event.defaultPrevented) {
              return
            }

            const clickHref = this.getAttribute('href')
            const clickExternalUrl = getExternalUrl(clickHref)
            if (!clickExternalUrl) {
              return
            }

            event.preventDefault()
            const nextUrl = clickExternalUrl.href
            openExternalModal(nextUrl, () => {
              window.open(nextUrl, '_blank', 'noopener,noreferrer')
            })
          })
        }
      }

      bindExternalLinks()
    }
  }

  window.ExternalLinkConfirm = ExternalLinkConfirm
})(window)
