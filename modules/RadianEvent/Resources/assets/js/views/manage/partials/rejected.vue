<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>
                            Documento electrónico mediante el cual el Adquiriente manifiesta que no acepta el documento de conformidad con el artículo 773 del Código de Comercio y en concordancia con el artículo 2.2.2.53.4. del Decreto 1074 de 2015, Único Reglamentario del Sector Comercio, Industria y Turismo. Este documento es para desaveniencias de tipo comercial, dado que el documento sobre el cual manifiesta el desacuerdo fue efectivamente Validado por la DIAN, en el sistema de Validación Previa.
                        </p>
                    </div>
                    <div class="col-md-12 mt-3">
                        <p><b>Motivo de Rechazo</b></p>
                            
                        <el-radio-group v-model="form.type_rejection_id">
                            <el-radio class="d-block" :label="1">Documento con inconsistencias.</el-radio>
                            <el-radio class="d-block" :label="2">Mercancía no entregada totalmente.</el-radio>
                            <el-radio class="d-block" :label="3">Mercancía no entregada parcialmente.</el-radio>
                            <el-radio class="d-block" :label="4">Servicio no prestado.</el-radio>
                        </el-radio-group>

                    </div>

                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Enviar rechazo</el-button>
            </div>
        </form>
    </el-dialog>

</template>

<script>

    export default {
        props: ['showDialog', 'record'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'co-radian-events',
                errors: {},
                form: {},
            }
        },
        created() {
            this.initForm()
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    type_rejection_id: 1,
                    event_code: '2',
                }
            },
            create() {
                this.titleDialog = `Motivo de Rechazo: ${this.record.type_document_name} - ${this.record.number}`
                this.form.id = this.record.id
            },
            submit() {
                this.loading_submit = true

                this.$http.post(`/${this.resource}/run-event`, this.form)
                    .then(response => {
                        console.log(response)
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        console.log(error)
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
        }
    }
</script>
