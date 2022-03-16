import nProgress from "nprogress";

window.oldUrl = window.location.href;
window.newUrl = "";
window.addEventListener("popstate", (e) => {
  window.newUrl = window.location.href;
  if (!compareUrl(newUrl, oldUrl)) {
    Livewire.emitTo(
      "livewire-spa.core.single-page",
      "update-url",
      window.location.href
    );
  }
});

document.addEventListener("DOMContentLoaded", () => {
  Livewire.hook("message.sent", (message, component) => {
    if (component.get("shouldHideProgressBar")) return;
    nProgress.start();
  });
  Livewire.hook("message.failed", (message, component) => {
    nProgress.done();
  });
  Livewire.hook("message.received", (message, component) => {
    nProgress.done();
  });
  Livewire.hook("message.processed", (message, component) => {
    if (component.fingerprint.name !== "livewire-spa.core.single-page") return;

    //change current url with currentUrl
    if (component.get("updateAddressBar")) {
      window.history.pushState({}, "", component.get("currentUrl"));
    }
    Livewire.emit("livewire-spa-updated-page", window.location.href);
  });
});

document.addEventListener("click", (event) => {
  const el = findLink(event.target);
  //   skip if el has native attribute
  if (el == null || el.hasAttribute("native")) {
    return;
  }

  const href = el.href;

  if (!href || !href.startsWith(window.location.origin)) return;

  event.preventDefault();

  if (compareUrl(href, window.location.href)) return;

  Livewire.emitTo("livewire-spa.core.single-page", "update-url", href, true);
});

function findLink(el) {
  if (el.tagName == "A" && el.href) {
    return el;
  } else if (el.parentElement) {
    return findLink(el.parentElement);
  } else {
    return null;
  }
}

function compareUrl(url1, url2) {
  console.log(url1.split("?")[0], url2.split("?")[0]);
  return url1.split("?")[0] === url2.split("?")[0];
}
