<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" @close="clickClose" width="30%" :close-on-click-modal="false" :show-close="false" append-to-body>
        <div class="row mt-3">
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label class="control-label">Fecha inicio periodo facturacion</label>
                    <el-date-picker v-model="form.invoice_period_start_date" type="date" value-format="yyyy-MM-dd" :clearable="false" ></el-date-picker>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label class="control-label">Fecha fin periodo facturacion</label>
                    <el-date-picker v-model="form.invoice_period_end_date" type="date" value-format="yyyy-MM-dd" :clearable="false" ></el-date-picker>
                </div>
            </div>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button @click.prevent="clickClose()">Cancelar</el-button>
            <el-button type="primary" @click="clickSave">Guardar</el-button>
        </span>
    </el-dialog>
</template>

<script>
    export default {
        props: ['showDialog', 'health_fields'],

        data() {
            return {
                titleDialog: 'Periodo de facturacion sector salud',
                loading: false,
                resource: 'co-documents',
                errors: {},
                form: {},
                company: {},
                locked_emission: {},
            }
        },

        async created() {
//            console.log(this.health_fields)
            if(this.health_fields !== null && this.health_fields !== 'undefined'){
                this.form = this.health_fields
            }
            else{
                this.form = {
                    invoice_period_start_date: null,
                    invoice_period_end_date: null,
                }
            }
//            console.log(this.form)
        },

        methods: {
            async create() {
            },

            validate(){
                if(!this.form.invoice_period_start_date)
                    return {
                        success: false,
                        message: 'El campo fecha de inicio del periodo de facturacion es obligatorio'
                    }

                if(!this.form.invoice_period_end_date)
                    return {
                        success: false,
                        message: 'El campo fecha fin del periodo de facturacion es obligatorio'
                    }

                return {
                    success: true
                }
            },

            clickSave() {
                let validate = this.validate()
                if(!validate.success)
                    return this.$message.error(validate.message)

                this.$emit('addHealthData', this.form);
                this.close()
            },

            clickClose() {
                this.close()
            },

            close(){
                this.$emit('update:showDialog', false)
            },
        }
    }
</script>
