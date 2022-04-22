<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10"></div>
            <div class="col-md-2 margin-bottom">
                <div class="spinner-border text-primary" role="status" :class="{ active: isNotActive }">
                    <span class="sr-only">Loading...</span>
                </div>
                <label for="file" class="btn btn-primary custom-file-upload">Upload</label>
                <input type="file" id="file" ref="file" @change="previewFiles" class="btn btn-primary margin-bottom" >
            </div>
            <div class="col-md-12 margin-bottom">
                <div class="alert alert-danger alert-dismissible fade show" role="alert" :class="{ error: isNotErrorActive }">
                    <p>{{ message }}</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="alert alert-success alert-dismissible fade show" role="alert" :class="{ success: isNotSuccessActive }">
                    <p>Upload successfully</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12" v-for = "file in files" :key="file.id">
                <div class="info-box" >
                    <span class="info-box-icon bg-success" style="width: 80px"><i class="fas fa-file"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ file.name }}</span>
                        <span class="info-box-number">{{ file.file_size }} kb</span>
                        <span class="info-box-text">
                            <a class="btn btn-sm" @click = "showFileDetail(file.id)">
                                <i class="fas fa-info-circle" ></i>
                            </a>
                            <a class="btn btn-sm" @click = "showDeleteFile(file.id)">
                                <i class="fas fa-trash"></i>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <Modal v-show="isModalVisible" @close="closeModal">
            <span class="card-header" slot="header">
                <h3 class="card-title">File Detail</h3>
            </span>
            <div class="card-body" slot="body">
                <div class="form-group">
                    <label>File name</label>
                    <h5>{{fileDetail.name}}</h5>
                </div>
                <div class="form-group">
                    <label>File size</label>
                    <h5>{{fileDetail.file_size}} KB</h5>
                </div>
                <div class="form-group">
                    <label>Time Upload</label>
                    <h5>{{fileDetail.created_at}}</h5>
                </div>
            </div>
            <div class="card-footer" slot="footer">
                <button class="modal-default-button btn-error" @click="closeModal">
                    Exit
                </button>
            </div>
        </Modal>

        <Modal v-show="isModalDeleteVisible" @close="closeModal">
            <div class="card-header" slot="header">
                <h3 class="card-title">Delete Product</h3>
            </div>
            <div class="card-body" slot="body">Bạn có muốn xóa Product này?</div>
            <div class="card-footer" slot="footer">
                <button class="modal-default-button btn-warning" @click="deleteFile()">
                    Delete
                </button>
                <button class="modal-default-button btn-error" @click="closeDeleteModal">
                    Exit
                </button>
            </div>
        </Modal>
    </div>
</template>

<script>
// import FileDetailComponent from "./FileDetailComponent.vue";
import Modal from "./Modal.vue"
export default {
    name: "ListFileComponent",
    components: {
        Modal,
    },
    data() {
        return {
            files : [],
            id : null,
            isModalVisible:false,
            isModalDeleteVisible:false,
            fileDetail : [],
            data: null,
            isNotActive: true,
            isNotErrorActive : true,
            isNotSuccessActive : true,
            message : "",
            file: ''
        }
    },
    created() {
        this.getFiles();
    },
    methods: {
        getFiles() {
            let uri = "http://127.0.0.1:8000/user/list-files";
            axios.get(uri).then(response => {
                this.files = response.data.data
            })
        },
        showFileDetail(id) {
            this.isModalVisible = true;
            let uri = "http://127.0.0.1:8000/user/file-detail/"+id;
            axios.get(uri).then(response => {
                console.log(response)
                this.fileDetail = response.data.data
            })
        },
        showDeleteFile(id) {
            this.id = id;
            this.isModalDeleteVisible = true;
            console.log(id);
        },
        deleteFile() {
            let uri = `http://127.0.0.1:8000/user/file-delete/${this.id}`;
            axios.get(uri).then(response => {
                console.log(uri);
                console.log(response.data);
                this.isModalDeleteVisible = false;
                this.getFiles();
            })
        },
        closeModal() {
            this.isModalVisible = false;
        },
        closeDeleteModal() {
            this.isModalDeleteVisible = false;
        },
        async previewFiles() {
            this.isNotActive = false;
            let formData = new FormData();
            // formData.append("file", event.target.files[0]);
            this.file = this.$refs.file.files[0];
            formData.append("file", this.file);
            let uri = "http://127.0.0.1:8000/user/upload-file";
            try {
                const response = await axios.post(uri, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                })
                if (response.status === 200) {
                    this.isNotActive = true;
                    this.isNotSuccessActive = false;
                    this.message = response.data.data;
                    this.getFiles();
                }
            } catch(err) {
                this.isNotActive = true;
                this.isNotErrorActive = false;
                this.message = err.response.data.message
            };
        }
    }
}
</script>

<style scoped>
    .margin-bottom {
        margin-top: 25px;
    }
    .custom-file-upload {
        border: 1px solid #ccc;
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
    }
    input[type="file"] {
        display: none;
    }
    .active {
        display: none;
    }
    .error {
        display: none;
    }
    .success {
        display: none;
    }
</style>
<!--}).then(response => {-->
<!--console.log(response);-->
<!--if (response.status === 200) {-->
<!--this.isNotActive = true;-->
<!--this.isNotSuccessActive = false;-->
<!--this.message = response.data.data;-->
<!--this.getFiles();-->
<!--}-->
<!--});-->
