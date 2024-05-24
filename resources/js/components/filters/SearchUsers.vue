<template>
    <div class="form-group"> 
        <label class="control-label">
            Vendedor
            <el-tooltip class="item" effect="dark" :content="`Usuario que generó los documentos`" placement="top-start">
                <i class="fa fa-info-circle"></i>
            </el-tooltip>
        </label>
        <el-select 
            v-model="user_id" 
            filterable
            class="full"
            placeholder="Escriba el nombre"
            :clearable="isClearable"
            @change="changeUser">
            <el-option v-for="option in records" :key="option.id" :value="option.id" :label="option.name"></el-option>
        </el-select>

        <small class="text-danger" v-if="errors_user_id" v-text="errors_user_id[0]"></small>
    </div>
</template> 

<script>

    export default {
        props: {
            errors_user_id: Array,
            isClearable: {
                type: Boolean,
                default: false
            }
        },
        data () {
            return {
                records: [],
                user_id: null,
            }
        },
        created() 
        {
            this.initData()
        },
        methods: {
            cleanValue()
            {
                this.user_id = null
            },
            changeUser()
            {
                this.$emit('changeUser', this.user_id)
            },
            async initData()
            {
                await this.getData()
                            .then(response => {
                                this.records = response.data
                            })
            },
            async getData() 
            {
                return await this.$http.get(`/users/search`)
            },
        }
    }
</script>