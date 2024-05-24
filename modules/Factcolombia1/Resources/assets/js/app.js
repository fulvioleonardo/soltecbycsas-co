// import SnackbarNotificationQueue from './mixins/SnackbarNotificationQueue';
// import VeeValidate, { Validator } from 'vee-validate';
// import v_es from 'vee-validate/dist/locale/es';
// import es from 'vuetify/es5/locale/es';
// // import ElementUI from 'element-ui';
// import Vuetify from 'vuetify';
// import 'vuetify/dist/vuetify.min.css'

// /**
//  * First we will load all of this project's JavaScript dependencies which
//  * includes Vue and other libraries. It is a great starting point when
//  * building robust, powerful web applications using Vue and Laravel.
//  */

// require('./bootstrap');

// window.Vue = require('vue');
// window.EventBus = new Vue();

// // Vuetify es
// Vue.use(Vuetify, {
//     lang: {
//         locales: {es},
//         current: 'es'
//     }
// });

// //  Element UI
// // Vue.use(ElementUI);

// // Vee validate
// Vue.use(VeeValidate);

// // Vee es
// Validator.localize('es', v_es);

// // Add errors request
// Vue.prototype.$setLaravelValidationErrorsFromResponse = function(errorResponse) {
//     if (!this.hasOwnProperty('$validator')) return;

//     this.$validator.errors.clear();

//     if (!errorResponse.hasOwnProperty('errors')) return;

//     let errorFields = Object.keys(errorResponse.errors);
//     let form_error = '';

//     if (errorFields.includes('form_error')) form_error += `${errorResponse.errors['form_error'].join()}.`;

//     for (let i = 0; i < errorFields.length; i++) {
//         let field = errorFields[i];
//         let errorString = errorResponse.errors[field].join(', ');

//         this.$validator.errors.add({
//             field: `${form_error}${field}`,
//             msg: errorString
//         });
//     }
// };

// // Add message request
// Vue.prototype.$setLaravelMessage = function(response) {
   

//     if ((response.hasOwnProperty('success')) && (response.hasOwnProperty('message')) && (!response.success)) this.$root.$emit('addSnackbarNotification', {text: response.message, color: 'error'});

//     if ((response.hasOwnProperty('success')) && (response.hasOwnProperty('message')) && (response.success)) this.$root.$emit('addSnackbarNotification', {text: response.message, color: 'success'});

//     if (response.hasOwnProperty('message') && (!response.hasOwnProperty('success'))) this.$root.$emit('addSnackbarNotification', {text: response.message, color: 'info'});
// };

// // Add errors server
// Vue.prototype.$setLaravelErrors = function(errorResponse) {
  

//     if ((errorResponse.hasOwnProperty('message')) && (errorResponse.message != '')) this.$root.$emit('addSnackbarNotification', {text: errorResponse.message, color: 'error'});

//     if ((errorResponse.hasOwnProperty('exception')) && (errorResponse.exception != '')) this.$root.$emit('addSnackbarNotification', {text: errorResponse.exception, color: 'error'});
// };

// /**
//  * The following block of code may be used to automatically register your
//  * Vue components. It will recursively scan this directory for the Vue
//  * components and automatically register them with their "basename".
//  *
//  * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
//  */

// // const files = require.context('./', true, /\.vue$/i)
// // files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

// Vue.component('tenant-document-form', require('./views/tenant/configuration/Configuration.vue'));
// Vue.component('tenant-configuration-documents', require('./views/tenant/configuration/Documents.vue'));
// Vue.component('tenant-quotation-quotation', require('./views/tenant/quotation/Quotation.vue'));
// Vue.component('notification-notification', require('./views/notification/Notification.vue'));
// Vue.component('tenant-document-document', require('./views/tenant/document/Document.vue'));
// Vue.component('tenant-report-tax', require('./views/tenant/report/tax/TaxReport.vue'));
// Vue.component('system-company-company', require('./views/system/company/Company.vue'));
// Vue.component('tenant-quotation-form', require('./views/tenant/quotation/Form.vue'));
// Vue.component('tenant-client-client', require('./views/tenant/client/Client.vue'));
// Vue.component('tenant-import-import', require('./views/tenant/import/Import.vue'));
// Vue.component('tenant-document-form', require('./views/tenant/document/Form.vue'));
// Vue.component('tenant-item-item', require('./views/tenant/item/Item.vue'));
// Vue.component('tenant-tax-tax', require('./views/tenant/tax/Tax.vue'));
// Vue.component('tenant-logo', require('./views/tenant/logo/Logo.vue'));
// Vue.component('menu-popover', require('./views/menu/Popover.vue'));
// Vue.component('auth-login', require('./views/auth/Login.vue'));

// Vue.component('system-document-company', require('./views/system/document/Document.vue'));

// /**
//  * Next, we will create a fresh Vue application instance and attach it to
//  * the page. Then, you may begin adding components to this application
//  * or customize the JavaScript scaffolding to fit your unique needs.
//  */

// const app = new Vue({
//     // el: '#main-wrapper',
//     mixins: [SnackbarNotificationQueue],
//     mounted () {
//         EventBus.$on('updateTheme', val => this.theme = val);
//     },
//     data: () => ({
//         drawer: null,
//         theme: false,
//         systemMenus: [],
//         tenantMenus: [{
//             icon: 'fas fa-file-alt',
//             url: '/client/documents',
//             title: 'Documentos'
//         }, {
//             icon: 'fas fa-calculator',
//             url: '/client/quotations',
//             title: 'Cotizaciones'
//         }, {
//             icon: 'people',
//             url: '/client/clients',
//             title: 'Clientes'
//         }, {
//             icon: 'shopping_cart',
//             url: '/client/items',
//             title: 'Productos'
//         }, {
//             icon: 'edit',
//             url: '/client/taxes',
//             title: 'Impuestos'
//         }, {
//             icon: 'settings',
//             url: '/client/configuration',
//             title: 'Configuración'
//         }]
//     })
// });
