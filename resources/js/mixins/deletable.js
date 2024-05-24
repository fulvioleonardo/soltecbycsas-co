export const deletable = {
    methods: {
        destroy(url) {
            return new Promise((resolve) => {
                this.$confirm('¿Desea eliminar el registro?', 'Eliminar', {
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    type: 'warning'
                }).then(() => {
                    this.$http.delete(url)
                        .then(res => {
                            if(res.data.success) {
                                this.$message.success(res.data.message)
                                resolve()
                            }else{
                                this.$message.error(res.data.message)
                                resolve()
                            }
                        })
                        .catch(error => {
                            if (error.response.status === 500) {
                                this.$message.error('Error al intentar eliminar');
                            } else {
                                console.log(error.response.data.message)
                            }
                        })
                }).catch(error => {
                    console.log(error)
                });
            })
        },
        anular(url) {
            return new Promise((resolve) => {
                this.$confirm('¿Desea anular el registro?', 'Anular', {
                    confirmButtonText: 'Anular',
                    cancelButtonText: 'Cancelar',
                    type: 'warning'
                }).then(() => {
                    this.$http.get(url)
                        .then(res => {
                            if (res.data.success) {
                                this.$message.success('Se anuló correctamente el registro')
                                resolve()
                            }
                        })
                        .catch(error => {
                            if (error.response.status === 500) {
                                this.$message.error('Error al intentar anular');
                            } else {
                                console.log(error.response.data.message)
                            }
                        })
                }).catch(error => {
                    console.log(error)
                });
            })
        },
        delete(url) {
            return new Promise((resolve) => {
                this.$confirm('¿Desea eliminar permanentemente el registro?', 'Anular', {
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    type: 'warning'
                }).then(() => {
                    this.$http.get(url)
                        .then(res => {
                            if (res.data.success) {
                                this.$message.success('Se anuló correctamente el registro')
                                resolve()
                            }
                        })
                        .catch(error => {
                            if (error.response.status === 500) {
                                this.$message.error('Error al intentar anular');
                            } else {
                                console.log(error.response.data.message)
                            }
                        })
                }).catch(error => {
                    console.log(error)
                });
            })
        },
        disable(url) {
            return new Promise((resolve) => {
                this.$confirm('¿Desea inhabilitar el registro?', 'Inhabilitar', {
                    confirmButtonText: 'Inhabilitar',
                    cancelButtonText: 'Cancelar',
                    type: 'warning'
                }).then(() => {
                    this.$http.get(url)
                        .then(res => {
                            if(res.data.success) {
                                this.$message.success(res.data.message)
                                resolve()
                            }else{
                                this.$message.error(res.data.message)
                                resolve()
                            }
                        })
                        .catch(error => {
                            if (error.response.status === 500) {
                                this.$message.error('Error al intentar inhabilitar');
                            } else {
                                console.log(error.response.data.message)
                            }
                        })
                }).catch(error => {
                    console.log(error)
                });
            })
        },
        enable(url) {
            return new Promise((resolve) => {
                this.$confirm('¿Desea habilitar el registro?', 'Habilitar', {
                    confirmButtonText: 'Habilitar',
                    cancelButtonText: 'Cancelar',
                    type: 'warning'
                }).then(() => {
                    this.$http.get(url)
                        .then(res => {
                            if(res.data.success) {
                                this.$message.success(res.data.message)
                                resolve()
                            }else{
                                this.$message.error(res.data.message)
                                resolve()
                            }
                        })
                        .catch(error => {
                            if (error.response.status === 500) {
                                this.$message.error('Error al intentar habilitar');
                            } else {
                                console.log(error.response.data.message)
                            }
                        })
                }).catch(error => {
                    console.log(error)
                });
            })
        },
        voided(url) {
            return new Promise((resolve) => {
                this.$confirm('¿Desea anular el registro?', 'Anular', {
                    confirmButtonText: 'Anular',
                    cancelButtonText: 'Cancelar',
                    type: 'warning'
                }).then(() => {
                    this.$http.get(url)
                        .then(res => {
                            if (res.data.success) {
                                this.$message.success(res.data.message)
                                resolve()
                            }
                        })
                        .catch(error => {
                            if (error.response.status === 500) {
                                this.$message.error('Error al intentar anular');
                            } else {
                                console.log(error.response.data.message)
                            }
                        })
                }).catch(error => {
                    console.log(error)
                });
            })
        },
        updateStateType(url) {
            return new Promise((resolve) => {
                this.$confirm('¿Desea modificar el estado del registro?', 'Modificar', {
                    confirmButtonText: 'Modificar',
                    cancelButtonText: 'Cancelar',
                    type: 'warning'
                }).then(() => {
                    this.$http.get(url)
                        .then(res => {
                            if (res.data.success) {
                                this.$message.success(res.data.message)
                                resolve()
                            }
                        })
                        .catch(error => {
                            if (error.response.status === 500) {
                                this.$message.error('Error al intentar modificar');
                            } else {
                                console.log(error.response.data.message)
                            }
                        })
                }).catch(error => {
                    console.log(error)
                    this.$eventHub.$emit('reloadData')
                });
            })
        },
        destroyAll(url) {

            return new Promise((resolve) => {      
                
                const context = this

                const h = this.$createElement;
                this.$msgbox({
                    title: 'Eliminar',
                    type: 'error',
                    message: h('p', null, [
                        h('p', null, [
                            h('b', null, '¿Desea eliminar todos los registros?')
                        ]),
                        h('p', null, 'Si los registros tienen transacciones asociadas (ventas, compras, etc), no se eliminarán.')
                    ]),
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    beforeClose: (action, instance, done) => {
                        if (action === 'confirm') {                         
                            
                            instance.confirmButtonLoading = true;
                            instance.confirmButtonText = 'Eliminando...';

                            context.$http.delete(url)
                                .then(res => {
                                    if(res.data.success) {
                                        this.$message.success(res.data.message)
                                        resolve()
                                    }else{
                                        this.$message.error(res.data.message)
                                        resolve()
                                    }
                                })
                                .catch(error => {
                                    if (error.response.status === 500) {
                                        this.$message.error('Ocurrió un error en el proceso');
                                    } else {
                                        console.log(error.response.data.message)
                                    }
                                }).then(()=>{
                                
                                    instance.confirmButtonLoading = false
                                    instance.confirmButtonText = 'Eliminar'
                                    done()
                                })

                        } else {
                            done();
                        }
                    }
                })
                .then(action => { 
                })
                .catch(action => { 
                });
            })
        }

    }
}
