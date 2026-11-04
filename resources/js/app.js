import "./bootstrap";

const sidebar = document.querySelector("[data-sidebar]");
const overlay = document.querySelector("[data-sidebar-overlay]");
const openButton = document.querySelector("[data-sidebar-open]");
const closeButton = document.querySelector("[data-sidebar-close]");

if (sidebar && overlay && openButton && closeButton) {
    const setSidebarOpen = (isOpen) => {
        sidebar.classList.toggle("translate-x-0", isOpen);
        sidebar.classList.toggle("-translate-x-full", !isOpen);
        overlay.classList.toggle("hidden", !isOpen);
        document.body.classList.toggle("overflow-hidden", isOpen);
    };

    openButton.addEventListener("click", () => setSidebarOpen(true));
    closeButton.addEventListener("click", () => setSidebarOpen(false));
    overlay.addEventListener("click", () => setSidebarOpen(false));
}

const deleteModal = document.querySelector("[data-delete-modal]");
const deleteMessage = document.querySelector("[data-delete-modal-message]");
const deleteCancel = document.querySelector("[data-delete-cancel]");
const deleteConfirm = document.querySelector("[data-delete-confirm]");
let deleteForm = null;

const closeDeleteModal = () => {
    deleteModal?.classList.add("hidden");
    deleteModal?.classList.remove("flex");
    deleteForm = null;
};

document.querySelectorAll("[data-confirm-delete]").forEach((form) => {
    form.addEventListener("submit", (event) => {
        event.preventDefault();
        deleteForm = form;
        if (deleteMessage) {
            deleteMessage.textContent = form.dataset.deleteMessage || "This action cannot be undone.";
        }
        deleteModal?.classList.remove("hidden");
        deleteModal?.classList.add("flex");
        deleteCancel?.focus();
    });
});

deleteCancel?.addEventListener("click", closeDeleteModal);
deleteConfirm?.addEventListener("click", () => {
    const form = deleteForm;
    closeDeleteModal();
    form?.submit();
});
deleteModal?.addEventListener("click", (event) => {
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
});
document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && deleteForm) {
        closeDeleteModal();
    }
});
