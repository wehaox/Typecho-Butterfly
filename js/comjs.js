function initializeCodeToolbar() {
  document.querySelectorAll("pre").forEach(pre => {
    const codeElement = pre.querySelector("code");
    const lineCount = pre.querySelector(".line-numbers-rows")?.querySelectorAll("span").length;
    if (codeElement && lineCount > 10) {
      pre.insertAdjacentHTML("afterbegin", '<div class="code-expand-btn"><i class="fas fa-angle-double-down"></i></div>');
    }
  });

  document.querySelectorAll(".code-expand-btn").forEach(btn => {
    btn.addEventListener('click', () => {
      const parentElement = btn.parentNode;
      const isExpanded = btn.classList.toggle("expand-done");
  
      Array.from(parentElement.children).forEach(element => {
        if (element.tagName === 'CODE') {
          element.classList.toggle('expand-bcode', isExpanded);
        }
      });
    });
  });
  

  document.querySelectorAll(".toolbar").forEach((toolbar) => {
    const iElement = document.createElement("i");
    iElement.className = "fas fa-angle-down expand";
    toolbar.appendChild(iElement);
  });
  const codeToolbars = document.querySelectorAll(".code-toolbar");
  const expandIcons = document.querySelectorAll(".expand");
  codeToolbars.forEach((toolbar, i) => {
    toolbar.id = `pre${i}`;
  });
  expandIcons.forEach((icon, i) => {
    icon.id = `pbtn${i}`;
  });
  expandIcons.forEach((icon) => {
    icon.removeEventListener("click", handleExpandClick);
  });
  expandIcons.forEach((icon) => {
    icon.addEventListener("click", handleExpandClick);
  });
}

function handleExpandClick(event) {
  const icon = event.currentTarget;
  const btid = icon.id;
  document.getElementById(btid).classList.toggle("bclose");

  const codeToolbar = icon.closest(".code-toolbar");
  const preId = codeToolbar.id;
  const preElement = document.querySelector(`#${preId} pre`);
  if (preElement) {
    preElement.style.display =
      preElement.style.display === "none" ? "" : "none";
  }
}

function tocCheck() {
  const tocElement = document.querySelector(".toc");
  if (tocElement && tocElement.innerHTML.length === 14) {
    const cardToc = document.getElementById("card-toc");
    const mobileTocButton = document.getElementById("mobile-toc-button");
    if (cardToc) cardToc.remove();
    if (mobileTocButton) mobileTocButton.remove();
  }
}
