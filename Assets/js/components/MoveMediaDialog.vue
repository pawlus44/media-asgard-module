<template>
    <div>
        <el-dialog title="Move Media" :visible.sync="dialogFormVisible" width="30%" class="move-media-dialog"  @open="fetchFolders">
            <el-form v-loading.body="loading" @submit.native.prevent="onSubmit()">
                <el-form-item label="To" :class="{'el-form-item is-error': form.errors.has('destinationFolder') }">
                    <el-select v-model="destinationFolder" placeholder="Select">
                        <el-option
                                v-for="(item, id) in options"
                                :key="id"
                                :label="item"
                                :value="id"
                                :loading="selectIsLoading">
                            <span v-html="item"></span>
                        </el-option>
                    </el-select>
                    <div class="el-form-item__error" v-if="form.errors.has('destinationFolder')"
                         v-text="form.errors.first('destinationFolder')"></div>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="closeDialog">{{ trans('core.button.cancel') }}</el-button>
                <el-button type="warning" @click="onSubmit()">{{ trans('core.move') }}</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import axios from 'axios';
    import Form from 'form-backend-validation';

    export default {
        props: {
        },
        data() {
            return {
                selectedMedia: [],
                dialogFormVisible: false,
                form: new Form(),
                loading: false,
                selectIsLoading: false,
                options: [],
                destinationFolder: '',
            };
        },
        methods: {
            onSubmit() {
                this.loading = true;
                this.form = new Form({
                    files: this.selectedMedia,
                    destinationFolder: this.destinationFolder,
                });
                this.form.post(route('api.media.media.move'))
                    .then((response) => {
                      this.loading = false;
                      if (response.status === 'something is not ok') {
                          this.$message({
                            type: 'warning',
                            message: response.message,
                          });
                      } else {
                        this.$message({
                            type: 'success',
                            message: response.message,
                          });
                        this.$events.emit('mediaWasMoved', response);
                      }
                      this.dialogFormVisible = false;
                    })
                    .catch((error) => {
                      this.loading = false;
                      if (error.response.data.status === 'undefined') {
                        this.$message({
                          type: 'error',
                          message: error.data.message,
                        });
                      } else if (error.response.status === 422) {
                        this.$message({
                          type: 'error',
                          message: error.response.data.message,
                        });
                      } else {
                        this.$message({
                          type: 'error',
                          message: error.data.message,
                        });
                      }
                    });
            },
            closeDialog() {
                this.form.clear();
                this.dialogFormVisible = false;
            },
            fetchFolders() {
                this.selectIsLoading = true;
                axios.get(route('api.media.folders.all-nestable'))
                    .then((response) => {
                        this.options = _.merge(response.data, { 0: 'Root' });
                        this.selectIsLoading = false;
                    });
            },
        },
        mounted() {
            this.$events.listen('moveMediaWasClicked', (eventData) => {
                this.selectedMedia = eventData;
                this.dialogFormVisible = true;
            });
        },
    };
</script>
<style>
    .move-media-dialog .el-select {
        width: 100%;
    }
</style>
