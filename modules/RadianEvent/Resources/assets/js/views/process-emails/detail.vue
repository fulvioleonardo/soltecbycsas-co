<template>
    <el-dialog width="70%" :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit" v-loading="loading">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">

                        <template v-if="records.length > 0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Asunto</th>
                                            <th>Enviado Por</th>
                                            <th>Respuesta Api</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(row, index) in records">
                                            <td>{{index+1}}</td>
                                            <td>{{ row.subject }}</td>
                                            <td>{{ row.from_name }} - {{ row.from_address }}</td>
                                            <td>{{ row.message_api }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </template>
                        <template v-else>
                            <p>No tiene registros asociados</p>
                        </template>

                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cerrar</el-button>
            </div>
        </form>
    </el-dialog>

</template>

<script>

    export default {
        props: ['showDialog', 'recordId'],
        data() {
            return {
                loading: false,
                titleDialog: null,
                resource: 'co-email-reading',
                records: [],
            }
        },
        created() { 
            this.records = []
        },
        methods: { 
            async create() {
                this.titleDialog = `Detalle del proceso de correos`

                this.loading = true

                await this.$http.get(`/${this.resource}/details/${this.recordId}`)
                    .then(response => {
                        this.records = response.data
                    }).then(() => {
                        this.loading = false
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
            },
        }
    }
</script>
