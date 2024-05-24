export default {
    data: () => ({
        toDate: moment().format('YYYY-MM-DD'),
        api_download: "",
    }),

    mounted() {
        axios
            .post(`/company`).then(response => {
                //this.$setLaravelMessage(response.data);
                this.form = response.data;
                //this.api_download = `${this.api}download/${this.company.identification_number}/`
                //if (this.hasOwnProperty('document')) this.document.currency_id = this.company.currency_id;
            })
            .catch(error => {
                this.$setLaravelValidationErrorsFromResponse(error.response.data);
                this.$setLaravelErrors(error.response.data);
            })
            .then(() => {
            });
    },

    computed: {
        currencySymbol() {
            return (this.company.currency != null) ? this.company.currency.symbol : '$';
        }
    },

    watch: {
        company: {
            handler(val) {
               // if (val.alert_certificate) this.$root.$emit('addSnackbarNotification', {text: `El certificado esta próximo a vencer por fecha de vigencia.`, color: 'warning'});
            },
            deep: true
        }
    },

    filters: {
        numberFormat: value => numeral(value).format('0,0.00')
    },

    methods: {
        getDepartment(val) {
            return axios
                        .post(`/departments/${val}`).then(response => {
                            this.$setLaravelMessage(response.data);
                            return response.data;
                        })
                        .catch(error => {
                            this.$setLaravelValidationErrorsFromResponse(error.response.data);
                            this.$setLaravelErrors(error.response.data);
                        });
        },

        getCities(val) {
            return axios
                        .post(`/cities/${val}`).then(response => {
                            this.$setLaravelMessage(response.data);
                            return response.data;
                        })
                        .catch(error => {
                            this.$setLaravelValidationErrorsFromResponse(error.response.data);
                            this.$setLaravelErrors(error.response.data);
                        });
        },

        getConcepts(val) {
            return axios
                        .post(`/concepts/${val}`).then(response => {
                            this.$setLaravelMessage(response.data);
                            return response.data;
                        })
                        .catch(error => {
                            this.$setLaravelValidationErrorsFromResponse(error.response.data);
                            this.$setLaravelErrors(error.response.data);
                        });
        },

        showColumn(columns, col){
            return columns.find(column => column.value == col).show
        },

        ratePrefix(tax = null) {
            if ((tax != null) && (!tax.is_fixed_value))
                return null;
            return (this.company.currency != null) ? this.company.currency.symbol : '$';
        },

        rateSuffix(tax) {
            return (tax.is_percentage) ? '%' : null;
        },
    }
}
