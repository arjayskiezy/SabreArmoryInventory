export default function dashboard() {
  return {
    // track which view is active (dashboard, catalog, requests, etc.)
    view: 'dashboard',

    // track modal state
    modal: {
      open: false,
      name: null,
    },

    // switch main view
    setView(name) {
      this.view = name
    },

    // open a modal by name
    openModal(name) {
      this.modal.open = true
      this.modal.name = name
    },

    // close any modal
    closeModal() {
      this.modal.open = false
      this.modal.name = null
    },
  }
}
