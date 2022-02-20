import nProgress from "nprogress";

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
        if (component.fingerprint.name !== "livewire-spa.core.single-page")
            return;

        //change current url with currentUrl
        window.history.pushState({}, "", component.get("currentUrl"));
        Livewire.emit("livewire-spa-updated-page", window.location.href);
    });
});

document.addEventListener("click", (event) => {
    function findLink(el) {
        if (el.tagName == "A" && el.href) {
            return el;
        } else if (el.parentElement) {
            return findLink(el.parentElement);
        } else {
            return null;
        }
    }

    const el = findLink(event.target);
    if (el == null || el.hasAttribute("native")) {
        return;
    }

    const href = el.href;
    if (!href || !href.startsWith(window.location.origin)) return;

    event.preventDefault();

    Livewire.emitTo("livewire-spa.core.single-page", "update-url", href);
});

window.addEventListener("popstate", (e) => {
    Livewire.emitTo(
        "livewire-spa.core.single-page",
        "update-url",
        window.location.href
    );
});
