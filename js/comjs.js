function tocCheck() {
  const tocElement = document.querySelector(".toc");
  if (tocElement && tocElement.innerHTML.length === 14) {
    const cardToc = document.getElementById("card-toc");
    const mobileTocButton = document.getElementById("mobile-toc-button");
    if (cardToc) cardToc.remove();
    if (mobileTocButton) mobileTocButton.remove();
  }
}
