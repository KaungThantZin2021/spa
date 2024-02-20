<template>
    <div class="container my-5">

        <!-- Search Bar -->
        <div class="row justify-content-end mb-3">
            <div class="col-md-2 col-sm-12">
                <button class="btn btn-primary" @click="create"><font-awesome-icon icon="fa-solid fa-circle-plus" /> Create</button>
            </div>
            <div class="col-6">
                <form @submit.prevent="view()">
                    <div class="input-group">
                        <input type="text" class="form-control" @keyup="view()" v-model="search" placeholder="Search...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form -->
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="card bg-secondary">
                    <h3 class="card-header text-light">{{ isEdit ? 'Edit' : 'Create' }}</h3>
                    <div class="card-body">
                        <form @submit.prevent="isEdit ? update() : store()">
                            <div class="form-group">
                                <label class="text-light">Name:</label>
                                <input type="text" class="form-control" :class="{ 'is-invalid' : product.errors.has('name') }" v-model="product.name">
                                <HasError :form="product" field="name"></HasError>
                            </div>
                            <div class="form-group">
                                <label class="text-light">Price:</label>
                                <input type="number" class="form-control" :class="{ 'is-invalid' : product.errors.has('price') }" v-model="product.price">
                                <HasError :form="product" field="price"></HasError>
                            </div>
                            <button class="btn btn-primary my-2"><i class="fas fa-save"></i> {{ isEdit ? 'Update' : 'Save' }}</button>
                        </form>
                    </div>
                </div>
                <AlertErrors class="my-3" :form="product"/>
            </div>

            <!-- Table -->
            <div class="col-md-8 col-sm-12">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody v-if="products.data != null">
                        <tr v-for="product in products.data" :key="product.id">
                            <td>{{ product.id }}</td>
                            <td>{{ product.name }}</td>
                            <td>{{ product.price }}</td>
                            <td>
                                <button class="btn btn-sm btn-success" @click="edit(product)"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" @click="destroy(product.id)"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td class="text-center text-muted" colspan="4">No data...!</td>
                        </tr>
                    </tbody>
                </table>
                
                <pagination v-model="pagination.page" :per-page="pagination.per_page" :records="pagination.records" @paginate="paginateCallback"/>
            </div>
        </div>
    </div>
</template>

<style>
input {
    color: whitesmoke !important;
    background-color: gray !important;
}

::placeholder {
    color: whitesmoke !important;
}

.invalid-feedback {
    color: whitesmoke;
    text-decoration: underline;
    text-decoration-color: red;
}
</style>

<script>
import axios from 'axios';
// import Form from 'vform';

export default {
    name: 'ProductComponent',
    data() {
        return {
            isEdit: false,
            products: {},
            product: new Form({
                id: '',  
                name: '',
                price: ''
            }),
            pagination: {
                page: 1,
                per_page: 5,
                records: 0
            },
            search: '',
        }
    },
    created() {
        this.view();
    },
    methods: {
        view() {
            this.$Progress.start();
            
            this.product.get(`/api/product?page=${this.pagination.page}&search=${this.search}`)
            .then(res => {
                this.products = res.data;
                this.pagination.records = res.data.total;
                this.pagination.per_page = res.data.per_page;
                this.$Progress.finish();
            })
            .catch(error => {
                this.$Progress.fail();

                Toast.fire({
                    icon: 'error',
                    title: error,
                    timer: 2000
                })
            });
        },
        create() {
            this.isEdit = false;
            this.product.clear();
            this.product.reset();
        },
        store() {
            this.product.post('/api/product')
            .then(res => {
                this.view();
                this.product.reset();

                Toast.fire({
                    icon: 'success',
                    title: 'Created in successfully'
                })
            }).catch(error =>{
                Toast.fire({
                    icon: 'error',
                    title: error,
                    timer: 2000
                })
            });
        },
        edit(product) {
            this.isEdit = true;
            this.product.clear();
            this.product.fill(product);
        },
        update() {
            this.product.post(`/api/product/${this.product.id}`)
            .then(res => {
                this.view();
                this.isEdit = false;
                this.product.reset();

                Toast.fire({
                    icon: 'success',
                    title: 'Updated in successfully'
                })
            }).catch(error =>{
                Toast.fire({
                    icon: 'error',
                    title: error,
                    timer: 2000
                })
            });
        },
        destroy(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you sure to delete?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.product.delete(`/api/product/${id}`)
                    .then(res => {
                        this.view();
                    }).catch(error =>{ 
                        this.$Progress.fail();
                        console.log(error);
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Deleted in successfully'
                    })
                }
            })
        },
        paginateCallback() {
            this.view();
        }
    },
}
</script>