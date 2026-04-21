(function () {
  function loadWeiboHot() {
    var container = document.getElementById('weibo-list')
    if (!container) {
      return
    }

    var apiUrl = container.getAttribute('data-api-url')
    if (!apiUrl) {
      return
    }

    fetch(apiUrl, {
      method: 'GET',
      credentials: 'same-origin',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(function (response) {
        if (!response.ok) {
          throw new Error('微博热搜请求失败')
        }
        return response.json()
      })
      .then(function (result) {
        if (!result || result.status !== 'success' || !result.data || typeof result.data.html !== 'string') {
          throw new Error('微博热搜返回异常')
        }
        container.innerHTML = result.data.html
      })
      .catch(function () {
        container.innerHTML = '<div class="weibo-list-item"><span class="weibo-title">微博热搜加载失败</span></div>'
      })
  }

  document.addEventListener('DOMContentLoaded', loadWeiboHot)
  document.addEventListener('pjax:complete', loadWeiboHot)
})()
