
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue'
import ElementUI from 'element-ui'
import Axios from 'axios'

import lang from 'element-ui/lib/locale/lang/es'
import locale from 'element-ui/lib/locale'
locale.use(lang)




//Vue.use(ElementUI)
Vue.use(ElementUI, {size: 'small'})
Vue.prototype.$eventHub = new Vue()
Vue.prototype.$http = Axios


Vue.prototype.$setStorage =   function(name, obj){
    localStorage.setItem(name, JSON.stringify(obj))
}

Vue.prototype.$getStorage = function(name){
    return JSON.parse(localStorage.getItem(name))
}

Vue.prototype.$removeStorage = function(name){
    localStorage.removeItem(name)
}

// Vuetify es
// Vue.use(Vuetify, {
//     lang: {
//         locales: {es},
//         current: 'es'
//     }
// });


// Vue.use(VeeValidate);


// require('./factcolombia');
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

//components colombia

Vue.component('tenant-note-form', require('@viewsModuleProColombia/tenant/document/note.vue'));
Vue.component('tenant-document-form', require('@viewsModuleProColombia/tenant/document/Form2.vue'));
Vue.component('tenant-document-form-aiu', require('@viewsModuleProColombia/tenant/document/FormAiu.vue'));
Vue.component('tenant-document-index', require('@viewsModuleProColombia/tenant/document/index.vue'));
Vue.component('system-company-company', require('@viewsModuleProColombia/system/company/index.vue'));
Vue.component('tenant-item-item', require('@viewsModuleProColombia/tenant/item/index.vue'));
Vue.component('tenant-tax-tax-co', require('@viewsModuleProColombia/tenant/tax/index.vue'));
Vue.component('tenant-taxes-form', require('@viewsModuleProColombia/tenant/tax/form.vue'));
Vue.component('tenant-client-client', require('@viewsModuleProColombia/tenant/client/index.vue'));
Vue.component('tenant-import-import', require('@viewsModuleProColombia/tenant/import/Import.vue'));


//components colombia
// Vue.component('tenant-document-form', require('@viewsModuleProColombia/tenant/configuration/Configuration.vue'));
// Vue.component('tenant-document-form', require('@viewsModuleProColombia/tenant/document/Form.vue'));

//colombia
Vue.component('tenant-document-form', require('@viewsModuleProColombia/tenant/document/Form2.vue'));
//Vue.component('tenant-configuration-configuration', require('@viewsModuleProColombia/tenant/configuration/Configuration.vue'));
Vue.component('tenant-configuration-general-data', require('@viewsModuleProColombia/tenant/configuration/GeneralData.vue'));
Vue.component('tenant-configuration-software', require('@viewsModuleProColombia/tenant/configuration/Software.vue'));
Vue.component('tenant-configuration-certificate', require('@viewsModuleProColombia/tenant/configuration/Certificate.vue'));
Vue.component('tenant-configuration-resolution', require('@viewsModuleProColombia/tenant/configuration/Resolution.vue'));
Vue.component('tenant-configuration-documents', require('@viewsModuleProColombia/tenant/configuration/Documents.vue'));
Vue.component('tenant-configuration-change-ambient', require('@viewsModuleProColombia/tenant/configuration/Production.vue'))
Vue.component('tenant-configuration-software-payroll', require('@viewsModuleProColombia/tenant/configuration/SoftwarePayroll.vue'));
Vue.component('tenant-configuration-software-eqdocs', require('@viewsModuleProColombia/tenant/configuration/SoftwareEqDocs.vue'));

//colombia

Vue.component('tenant-dashboard-index', require('../../modules/Dashboard/Resources/assets/js/views/index.vue'));
Vue.component('x-graph', require('./components/graph/src/Graph.vue'));
Vue.component('x-graph-line', require('./components/graph/src/GraphLine.vue'));
Vue.component('tenant-companies-form', require('./views/tenant/companies/form.vue'));
Vue.component('tenant-companies-logo', require('./views/tenant/companies/logo.vue'));
Vue.component('tenant-certificates-index', require('./views/tenant/certificates/index.vue'));
Vue.component('tenant-certificates-form', require('./views/tenant/certificates/form.vue'));
Vue.component('tenant-configurations-form', require('./views/tenant/configurations/form.vue'));
Vue.component('tenant-configurations-visual', require('./views/tenant/configurations/visual.vue'));
Vue.component('tenant-configurations-pdf', require('./views/tenant/configurations/pdf_templates.vue'));
// Vue.component('tenant-establishments-form', require('./views/tenant/establishments/form.vue'));
// Vue.component('tenant-series-form', require('./views/tenant/series/form.vue'));
Vue.component('tenant-bank_accounts-index', require('./views/tenant/bank_accounts/index.vue'));
Vue.component('tenant-items-index', require('./views/tenant/items/index.vue'));
Vue.component('tenant-persons-index', require('./views/tenant/persons/index.vue'));
// Vue.component('tenant-customers-index', require('./views/tenant/customers/index.vue'));
// Vue.component('tenant-suppliers-index', require('./views/tenant/suppliers/index.vue'));
Vue.component('tenant-users-form', require('./views/tenant/users/form.vue'));
Vue.component('tenant-documents-index', require('./views/tenant/documents/index.vue'));
Vue.component('tenant-documents-invoice', require('./views/tenant/documents/invoice.vue'));
Vue.component('tenant-documents-invoicetensu', require('./views/tenant/documents/invoicetensu.vue'));
Vue.component('tenant-documents-note', require('./views/tenant/documents/note.vue'));
Vue.component('tenant-summaries-index', require('./views/tenant/summaries/index.vue'));
Vue.component('tenant-voided-index', require('./views/tenant/voided/index.vue'));
Vue.component('tenant-search-index', require('./views/tenant/search/index.vue'));
Vue.component('tenant-options-form', require('./views/tenant/options/form.vue'));
Vue.component('tenant-unit_types-index', require('./views/tenant/unit_types/index.vue'));
Vue.component('tenant-detraction_types-index', require('./views/tenant/detraction_types/index.vue'));
Vue.component('tenant-users-index', require('./views/tenant/users/index.vue'));
Vue.component('tenant-establishments-index', require('./views/tenant/establishments/index.vue'));
Vue.component('tenant-charge_discounts-index', require('./views/tenant/charge_discounts/index.vue'));
Vue.component('tenant-banks-index', require('./views/tenant/banks/index.vue'));
Vue.component('tenant-exchange_rates-index', require('./views/tenant/exchange_rates/index.vue'));
Vue.component('tenant-currency-types-index', require('./views/tenant/currency_types/index.vue'));
Vue.component('tenant-retentions-index', require('./views/tenant/retentions/index.vue'));
Vue.component('tenant-retentions-form', require('./views/tenant/retentions/form.vue'));
Vue.component('tenant-perceptions-index', require('./views/tenant/perceptions/index.vue'));
Vue.component('tenant-perceptions-form', require('./views/tenant/perceptions/form.vue'));
Vue.component('tenant-dispatches-index', require('./views/tenant/dispatches/index.vue'));
Vue.component('tenant-dispatches-form', require('./views/tenant/dispatches/form.vue'));
Vue.component('tenant-dispatches-create', require('./views/tenant/dispatches/create.vue'));
Vue.component('tenant-purchases-index', require('./views/tenant/purchases/index.vue'));
Vue.component('tenant-purchases-form', require('./views/tenant/purchases/form.vue'));
Vue.component('tenant-purchases-edit', require('./views/tenant/purchases/form_edit.vue'));

Vue.component('tenant-purchases-items', require('./views/tenant/dispatches/items.vue'));
Vue.component('tenant-attribute_types-index', require('./views/tenant/attribute_types/index.vue'));
Vue.component('tenant-calendar', require('./views/tenant/components/calendar.vue'));
Vue.component('tenant-warehouses', require('./views/tenant/components/warehouses.vue'));
Vue.component('tenant-calendar-quotation', require('./views/tenant/components/calendarquotations.vue'));

//Vue.component('tenant-calendar', require('./views/tenant/components/calendar.vue'));
Vue.component('tenant-product', require('./views/tenant/components/products.vue'));


Vue.component('tenant-tasks-lists', require('./views/tenant/tasks/lists.vue'));
Vue.component('tenant-tasks-form', require('./views/tenant/tasks/form.vue'));
Vue.component('tenant-reports-consistency-documents-lists', require('./views/tenant/reports/consistency-documents/lists.vue'));
Vue.component('tenant-contingencies-index', require('./views/tenant/contingencies/index.vue'));

Vue.component('tenant-quotations-index', require('./views/tenant/quotations/index.vue'));
Vue.component('tenant-quotations-form', require('./views/tenant/quotations/form.vue'));
Vue.component('tenant-quotations-edit', require('./views/tenant/quotations/form_edit.vue'));


Vue.component('tenant-sale-notes-index', require('./views/tenant/sale_notes/index.vue'));
Vue.component('tenant-sale-notes-form', require('./views/tenant/sale_notes/form.vue'));
Vue.component('tenant-pos-index', require('./views/tenant/pos/index.vue'));
Vue.component('tenant-pos-configuration', require('./views/tenant/pos/configuration.vue'));
Vue.component('tenant-pos-documents', require('./views/tenant/pos/documents.vue'));
Vue.component('tenant-pos-refund', require('./views/tenant/pos/refund.vue'));



Vue.component('cash-index', require('./views/tenant/cash/index.vue'));
Vue.component('tenant-card-brands-index', require('./views/tenant/card_brands/index.vue'));

Vue.component('tenant-payment-method-index', require('./views/tenant/payment_method/index.vue'));
Vue.component('tenant-payment-method-index', require('./views/tenant/payment_method/index.vue'));



// Modules
Vue.component('inventory-index', require('../../modules/Inventory/Resources/assets/js/inventory/index.vue'));
Vue.component('inventory-transfers-index', require('../../modules/Inventory/Resources/assets/js/transfers/index.vue'));
Vue.component('warehouses-index', require('../../modules/Inventory/Resources/assets/js/warehouses/index.vue'));
Vue.component('tenant-report-kardex-index', require('../../modules/Inventory/Resources/assets/js/kardex/index.vue'));
Vue.component('tenant-inventories-form', require('../../modules/Inventory/Resources/assets/js/config/form.vue'));
Vue.component('tenant-expenses-index', require('../../modules/Expense/Resources/assets/js/views/expenses/index.vue'));
Vue.component('tenant-expenses-form', require('../../modules/Expense/Resources/assets/js/views/expenses/form.vue'));
Vue.component('tenant-account-export', require('../../modules/Account/Resources/assets/js/views/account/export.vue'));
Vue.component('tenant-account-summary-report', require('../../modules/Account/Resources/assets/js/views/summary_report/index.vue'));
Vue.component('tenant-account-format', require('../../modules/Account/Resources/assets/js/views/account/format.vue'));
Vue.component('tenant-company-accounts', require('../../modules/Account/Resources/assets/js/views/company_accounts/form.vue'));

Vue.component('tenant-documents-not-sent', require('../../modules/Document/Resources/assets/js/views/documents/not_sent.vue'));
Vue.component('tenant-report-purchases-index', require('../../modules/Report/Resources/assets/js/views/purchases/index.vue'));
Vue.component('tenant-report-documents-index', require('../../modules/Report/Resources/assets/js/views/documents/index.vue'));

Vue.component('tenant-report-document-pos-index', require('../../modules/Report/Resources/assets/js/views/document_pos/index.vue'));

Vue.component('tenant-report-customers-index', require('../../modules/Report/Resources/assets/js/views/customers/index.vue'));
Vue.component('tenant-report-items-index', require('../../modules/Report/Resources/assets/js/views/items/index.vue'));
Vue.component('tenant-report-sale_notes-index', require('../../modules/Report/Resources/assets/js/views/sale_notes/index.vue'));
Vue.component('tenant-report-quotations-index', require('../../modules/Report/Resources/assets/js/views/quotations/index.vue'));
Vue.component('tenant-report-cash-index', require('../../modules/Report/Resources/assets/js/views/cash/index.vue'));
Vue.component('tenant-index-configuration', require('../../modules/BusinessTurn/Resources/assets/js/views/configurations/index.vue'));
Vue.component('tenant-report-document_hotels-index', require('../../modules/Report/Resources/assets/js/views/document_hotels/index.vue'));
Vue.component('tenant-report-commercial_analysis-index', require('../../modules/Report/Resources/assets/js/views/commercial_analysis/index.vue'));
Vue.component('tenant-offline-configurations-index', require('../../modules/Offline/Resources/assets/js/views/offline_configurations/index.vue'));
Vue.component('tenant-series-configurations-index', require('../../modules/Document/Resources/assets/js/views/series_configurations/index.vue'));
Vue.component('tenant-validate-documents-index', require('../../modules/Document/Resources/assets/js/views/validate_documents/index.vue'));
Vue.component('tenant-report-document-detractions-index', require('../../modules/Report/Resources/assets/js/views/document-detractions/index.vue'));
Vue.component('tenant-report-commissions-index', require('../../modules/Report/Resources/assets/js/views/commissions/index.vue'));
Vue.component('tenant-report-order-notes-consolidated-index', require('../../modules/Report/Resources/assets/js/views/order_notes_consolidated/index.vue'));
Vue.component('tenant-report-general-items-index', require('../../modules/Report/Resources/assets/js/views/general_items/index.vue'));
Vue.component('tenant-report-order-notes-general-index', require('../../modules/Report/Resources/assets/js/views/order_notes_general/index.vue'));
Vue.component('tenant-report-sales-consolidated-index', require('../../modules/Report/Resources/assets/js/views/sales_consolidated/index.vue'));

Vue.component('tenant-report-user-commissions-index', require('../../modules/Report/Resources/assets/js/views/user_commissions/index.vue'));

Vue.component('tenant-report-tax-index', require('../../modules/Report/Resources/assets/js/views/taxes/index.vue'));

Vue.component('tenant-report-co-remissions-index', require('../../modules/Report/Resources/assets/js/views/co-remissions/index.vue'));
Vue.component('tenant-report-co-items-sold-index', require('@viewsModuleReport/co-items-sold/index.vue'));
Vue.component('tenant-report-co-sales-book-index', require('@viewsModuleReport/co-sales-book/index.vue'));


Vue.component('tenant-categories-index', require('../../modules/Item/Resources/assets/js/views/categories/index.vue'));
Vue.component('tenant-brands-index', require('../../modules/Item/Resources/assets/js/views/brands/index.vue'));
Vue.component('tenant-incentives-index', require('../../modules/Item/Resources/assets/js/views/incentives/index.vue'));

Vue.component('tenant-ecommerce-configuration-info', require('../../modules/Ecommerce/Resources/assets/js/views/configuration/index.vue'));
Vue.component('tenant-ecommerce-configuration-culqi', require('../../modules/Ecommerce/Resources/assets/js/views/configuration_culqi/index.vue'));
Vue.component('tenant-ecommerce-configuration-paypal', require('../../modules/Ecommerce/Resources/assets/js/views/configuration_paypal/index.vue'));
Vue.component('tenant-ecommerce-configuration-logo', require('../../modules/Ecommerce/Resources/assets/js/views/configuration_logo/index.vue'));
Vue.component('tenant-ecommerce-configuration-social', require('../../modules/Ecommerce/Resources/assets/js/views/configuration_social/index.vue'));
Vue.component('tenant-ecommerce-configuration-tag', require('../../modules/Ecommerce/Resources/assets/js/views/configuration_tags/index.vue'));

Vue.component('tenant-purchase-quotations-index', require('../../modules/Purchase/Resources/assets/js/views/purchase-quotations/index.vue'));
Vue.component('tenant-purchase-quotations-form', require('../../modules/Purchase/Resources/assets/js/views/purchase-quotations/form.vue'));

Vue.component('tenant-purchase-orders-index', require('../../modules/Purchase/Resources/assets/js/views/purchase-orders/index.vue'));
Vue.component('tenant-purchase-orders-form', require('../../modules/Purchase/Resources/assets/js/views/purchase-orders/form.vue'));
Vue.component('tenant-purchase-orders-generate', require('../../modules/Purchase/Resources/assets/js/views/purchase-orders/generate.vue'));

Vue.component('moves-index', require('../../modules/Inventory/Resources/assets/js/moves/index.vue'));
Vue.component('inventory-form-masive', require('../../modules/Inventory/Resources/assets/js/transfers/form_masive.vue'));

Vue.component('tenant-report-kardex-master', require('../../modules/Inventory/Resources/assets/js/kardex_master/index.vue'));
Vue.component('tenant-report-kardex-lots', require('../../modules/Inventory/Resources/assets/js/kardex/lots.vue'));
Vue.component('tenant-report-kardex-series', require('../../modules/Inventory/Resources/assets/js/kardex/series.vue'));

Vue.component('tenant-order-notes-index', require('../../modules/Order/Resources/assets/js/views/order_notes/index.vue'));
Vue.component('tenant-order-notes-form', require('../../modules/Order/Resources/assets/js/views/order_notes/form.vue'));
Vue.component('tenant-order-notes-edit', require('../../modules/Order/Resources/assets/js/views/order_notes/form_edit.vue'));
Vue.component('tenant-report-valued-kardex', require('../../modules/Inventory/Resources/assets/js/valued_kardex/index.vue'));

//Finance
Vue.component('tenant-finance-global-payments-index', require('../../modules/Finance/Resources/assets/js/views/global_payments/index.vue'));
Vue.component('tenant-finance-balance-index', require('../../modules/Finance/Resources/assets/js/views/balance/index.vue'));
Vue.component('tenant-finance-payment-method-types-index', require('../../modules/Finance/Resources/assets/js/views/payment_method_types/index.vue'));
Vue.component('tenant-finance-unpaid-index', require('@viewsModuleFinance/unpaid/index.vue'));
Vue.component('tenant-finance-to-pay-index', require('@viewsModuleFinance/to_pay/index.vue'));
Vue.component('tenant-finance-income-index', require('@viewsModuleFinance/income/index.vue'));
Vue.component('tenant-finance-income-form', require('@viewsModuleFinance/income/form.vue'));
Vue.component('tenant-income-types-index', require('@viewsModuleFinance/income_types/index.vue'));
Vue.component('tenant-income-reasons-index', require('@viewsModuleFinance/income_reasons/index.vue'));


//Sale
Vue.component('tenant-sale-opportunities-index', require('@viewsModuleSale/sale_opportunities/index.vue'));
Vue.component('tenant-sale-opportunities-form', require('@viewsModuleSale/sale_opportunities/form.vue'));
Vue.component('tenant-payment-method-types-index', require('@viewsModuleSale/payment_method_types/index.vue'));
Vue.component('tenant-contracts-index', require('@viewsModuleSale/contracts/index.vue'));
Vue.component('tenant-contracts-form', require('@viewsModuleSale/contracts/form.vue'));
Vue.component('tenant-production-orders-index', require('@viewsModuleSale/production_orders/index.vue'));

//Purchase

Vue.component('tenant-fixed-asset-items-index', require('@viewsModulePurchase/fixed_asset_items/index.vue'));
Vue.component('tenant-fixed-asset-purchases-index', require('@viewsModulePurchase/fixed_asset_purchases/index.vue'));
Vue.component('tenant-fixed-asset-purchases-form', require('@viewsModulePurchase/fixed_asset_purchases/form.vue'));

//Expense

Vue.component('tenant-expense-types-index', require('@viewsModuleExpense/expense_types/index.vue'));
Vue.component('tenant-expense-reasons-index', require('@viewsModuleExpense/expense_reasons/index.vue'));
Vue.component('tenant-expense-method-types-index', require('@viewsModuleExpense/expense_method_types/index.vue'));

//technical Services
Vue.component('tenant-technical-services-index', require('@viewsModuleSale/technical-services/index.vue'));
Vue.component('tenant-user-commissions-index', require('@viewsModuleSale/user-commissions/index.vue'));

//payroll
Vue.component('tenant-workers-index', require('@viewsModulePayroll/workers/index.vue'));
Vue.component('tenant-document-payrolls-index', require('@viewsModulePayroll/document-payrolls/index.vue'));
Vue.component('tenant-document-payrolls-form', require('@viewsModulePayroll/document-payrolls/form.vue'));
// Vue.component('tenant-type-workers-index', require('@viewsModulePayroll/type-workers/index.vue'));
// Vue.component('tenant-sub-type-workers-index', require('@viewsModulePayroll/sub-type-workers/index.vue'));

// documento soporte
Vue.component('tenant-support-documents-index', require('@viewsModulePurchase/support-documents/index.vue'));
Vue.component('tenant-support-documents-form', require('@viewsModulePurchase/support-documents/form.vue'));
Vue.component('tenant-support-documents-form-adjust-note', require('@viewsModulePurchase/support-documents/form_adjust_note.vue'));

// evento radian
Vue.component('tenant-co-radian-event-reception-index', require('@viewsModuleRadianEvent/reception/index.vue'));
Vue.component('tenant-co-radian-event-manage-index', require('@viewsModuleRadianEvent/manage/index.vue'));
Vue.component('tenant-co-radian-event-process-emails-index', require('@viewsModuleRadianEvent/process-emails/index.vue'));


// advanced-configuration
Vue.component('tenant-advanced-configuration-index', require('@viewsModuleProColombia/tenant/advanced-configuration/index.vue'));

// Remissions
Vue.component('tenant-co-remissions-index', require('@viewsModuleSale/co-remissions/index.vue'));
Vue.component('tenant-co-remissions-form', require('@viewsModuleSale/co-remissions/form.vue'));


// System
Vue.component('system-clients-index', require('./views/system/clients/index.vue'));
Vue.component('system-clients-form', require('./views/system/clients/form.vue'));
Vue.component('system-users-form', require('./views/system/users/form.vue'));

Vue.component('system-certificate-index', require('./views/system/certificate/index.vue'));
Vue.component('system-companies-form', require('./views/system/companies/form.vue'));



Vue.component('system-plans-index', require('./views/system/plans/index.vue'));
Vue.component('system-plans-form', require('./views/system/plans/form.vue'));

Vue.component('x-input-service', require('./components/InputService.vue'));

Vue.component('tenant-items-ecommerce-index', require('./views/tenant/items_ecommerce/index.vue'));
Vue.component('tenant-ecommerce-cart', require('./views/tenant/ecommerce/cart_dropdown.vue'));
Vue.component('tenant-tags-index', require('./views/tenant/tags/index.vue'));
Vue.component('tenant-promotions-index', require('./views/tenant/promotions/index.vue'));

Vue.component('tenant-item-sets-index', require('./views/tenant/item_sets/index.vue'));
Vue.component('tenant-person-types-index', require('./views/tenant/person_types/index.vue'));

Vue.component('tenant-orders-index', require('./views/tenant/orders/index.vue'));

//Cuenta
Vue.component('tenant-account-payment-index', require('./views/tenant/account/payment_index.vue'));
Vue.component('tenant-account-configuration-index', require('./views/tenant/account/configuration.vue'));

//auto update
Vue.component('system-update', require('./views/system/update/index.vue'));






const app = new Vue({
    el: '#main-wrapper',
    // mixins: [SnackbarNotificationQueue],
});
