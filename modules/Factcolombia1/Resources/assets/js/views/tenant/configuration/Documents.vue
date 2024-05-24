<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Configuración</span> </li>
                <li><span class="text-muted">Documentos</span></li>
            </ol>
            <div class="right-wrapper pull-right">
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Prefijo</th>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th>Generadas</th>
                                <th>Número de resolución</th>
                                <th>Fecha resolución</th>
                                <th>Fecha resolución hasta</th>
                                <th>Clave técnica</th>
                                <th>Descripcion</th>
                                <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in typeDocuments" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ row.name }}</td>
                            <td>{{ row.prefix }}</td>
                            <td>{{ row.from }}</td>
                            <td>{{ row.to }}</td>
                            <td>{{ row.generated }}</td>
                            <td>{{ row.resolution_number }}</td>
                            <td>{{ row.resolution_date }}</td>
                            <td>{{ row.resolution_date_end }}</td>
                            <td>{{ row.technical_key }}</td>
                            <td>{{ row.description }}</td>
                            <td class="text-right">
                                <template>
                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="editItem(row)">Editar</button>
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <edit-form @refresh="refresh" :showDialog.sync="dialog" :record="item" ></edit-form>
    </div>
</template>

<script>
    //import Helper from '../../../mixins/Helper';
    //import DataTable from '../../../components/DataTableConfigurationDocuments'
    import EditForm from './partial/edit'

    export default {
      //  mixins: [Helper],
        components:{ EditForm },

        props: {
            route: {
                required: true
            }
        },

        data: () => ({
            loadingCompany: false,
            typeDocuments: [],
            dialog: false,
            item: {},
            loadDataTable: false,
            items: [],
            item:null
        }),

        computed: {

        },

        mounted() {
            this.refresh();
        },

        methods: {
            refresh() {
                axios.get(`/co-configuration-all`).then(response => {
                    this.typeDocuments = response.data.typeDocuments;
                }).catch(error => {
                   // this.$setLaravelValidationErrorsFromResponse(error.response.data);
                   // this.$setLaravelErrors(error.response.data);
                }).then(() => {});
            },

            editItem(item) {
                this.item = JSON.parse(JSON.stringify(item));
                this.dialog = true;
            },

            validate(scope, model = null, models = null, modelObject = null) {
                debugger
                this.$validator.validateAll(scope).then(valid => {
                    if (valid) {
                        modelObject.prefix = modelObject.prefix.toUpperCase()
                        this.loadingCompany = true;
                        this.loadDataTable = true;
                        axios.post(`/client/configuration/type_document/${modelObject.id}`, modelObject).then(response => {
                            if (response.data.success) this.refresh();
                            //this.$setLaravelMessage(response.data);
                        }).catch(error => {
                            //this.$setLaravelValidationErrorsFromResponse(error.response.data);
                            //this.$setLaravelErrors(error.response.data);
                        }).then(() => {
                            this.loadingCompany = false;
                            this.dialog = false;
                            this.loadDataTable = false;
                        });
                    }
                });
            }
        }
    }
</script>

<style lang="scss">
    .input-uppercase input {
        text-transform: uppercase
    }
</style>
