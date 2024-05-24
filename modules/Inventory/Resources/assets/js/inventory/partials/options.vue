<template>
    <div>
        <el-dialog :title="titleDialog" :visible="showDialog" @open="create"
                :close-on-click-modal="false"
                :close-on-press-escape="false"
                :show-close="false">
            <div class="row">
                <el-button  type="primary"  class="float-right" @click="download">Descargar constancia.</el-button>
            </div>
            <span slot="footer" class="dialog-footer row">
                <div class="col-md-6">
                    <el-button @click="clickClose">Cerrar</el-button>
                </div>
            </span>
        </el-dialog>

    </div>
</template>

<script>


    export default {

        props: ['showDialog', 'recordId', 'showClose'],
        data() {
            return {
                titleDialog: null,
                loading: false,
                resource: 'inventory',
                loading_submit:false,
                showDialogOptions: false,
                documentNewId: null,
                activeName: 'first',
            }
        },
        created() {
            this.initForm()
        },
        methods: {
            initForm() {

            },
            create() {

            },
            clickFinalize() {
                location.href = `/${this.resource}`
            },
            clickClose() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
            clickDownload(){
                window.open(`/downloads/saleNote/sale_note/${this.form.external_id}`, '_blank');
            },
            clickToPrint(format){
                window.open(`/${this.resource}/print/${this.form.id}/${format}`, '_blank');
            },

        }
    }
</script>
