// export default function dashboard() {
//   return {
//     // track which view is active (dashboard, catalog, requests, etc.)
//     view: 'dashboard',

//     // track modal state
//     modal: {
//       open: false,
//       name: null,
//     },

//     // switch main view
//     setView(name) {
//       this.view = name
//     },

//     // open a modal by name
//     openModal(name) {
//       this.modal.open = true
//       this.modal.name = name
//     },

//     // close any modal
//     closeModal() {
//       this.modal.open = false
//       this.modal.name = null
//     },
//   }
// }

export default function dashboard() {
  return {
    // track active view
    view: 'dashboard',

    // modal state
    modal: {
      open: false,
      name: null,   // for static modals
      url: null,    // for dynamic Symfony modals
      content: '',  // fetched HTML
    },

    // switch sidebar views
    setView(name) {
      this.view = name
    },

    // open a static modal
    openModal(name) {
      this.modal.open = true
      this.modal.name = name
      this.modal.url = null
      this.modal.content = ''
    },

    // open a dynamic modal by URL
    async loadModal(url) {
      this.modal.open = true
      this.modal.name = null
      this.modal.url = url
      this.modal.content = 'Loading...'
      try {
        let res = await fetch(url)
        this.modal.content = await res.text()
      } catch (err) {
        this.modal.content = `<p class="text-red-600">Failed to load modal</p>`
      }
    },

    // close modal
    closeModal() {
      this.modal.open = false
      this.modal.name = null
      this.modal.url = null
      this.modal.content = ''
    },
  }
}
