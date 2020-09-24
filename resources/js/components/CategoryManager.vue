<template>
    <form @submit.prevent="saveCategories">
        <a @click="addCategory" class="add">+ Add category</a>
        <div>{{ feedback }}</div>
        <div v-for="(category, index) in categories" :key="category.id">
            <input type="text" :value="category.name" @input="update($event, 'name', index)" :ref="category.name" required>
            <input type="number" :value="category.display_order" @input="update($event, 'display_order', index)" required>
            <a @click="removeCategory(index)" class="remove">delete</a>
            <div>
                <!-- <img v-if="category.image" :src="`/images/${category.image}`" width="100"> -->
                <!-- <img v-if="category.image" :src="`/storage/images/${category.image}`" width="200"/> -->
                <!-- <a @click="changeImage(index)" class="change">change</a> -->
                <!-- <label v-else>Image: </label> -->
                <!-- <input type="text" :value="category.image" @input="update($event, 'image', index)" required> -->
                <drop-zone 
                    v-bind:id="'dz'+index" 
                    :options="dropzoneOptions"
                    :destroyDropzone="false"
                    v-on:vdropzone-mounted="manualImageUpload(index, category.image)" 
                    v-on:vdropzone-success="update($event, 'image', index)"
                    v-on:vdropzone-removed-file="removedfile($event, index)" 
                    ref="dropzone"
                />
            </div>
            <hr>
        </div>
        <button type="submit">Save</button>
        <div>{{ feedback }}</div>
        <ul>
            <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
        </ul>
    </form>
</template>

<script>
    import vue2Dropzone from 'vue2-dropzone';
    import 'vue2-dropzone/dist/vue2Dropzone.min.css';

    function newCategory(display_order) {
        return{
            id: 0,
            name: '',
            image: '',
            display_order: display_order+1
        }
    }

    export default {
        components: {
            dropZone: vue2Dropzone
        },
        data() {
            return {
                dropzoneOptions: {
                    url: '/api/add-image',
                    thumbnailWidth: 200,
                    maxFiles: 1,
                    acceptedFiles: 'image/*',
                    addRemoveLinks: true,
                    previewTemplate: this.previewTemplate(),
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                    },
                    success(file, res){
                        file.filename = res;
                    }
                },
                errors: []
            };
        },
        computed: {
            categories() {
                return this.$store.state.categories;
            },
            feedback() {
                return this.$store.state.feedback;
            }
        },
        created() {
            // axios.defaults.headers.common["Authorization"] = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjZiNmU0ZTZjYzMwOTMzYWU1N2IzZjZmM2YxNmQ1ZDhjOTAwOGNlM2ZmMWIyZDdlYWVlM2I3MjhjNjI2MGU4YTMwMjA5ZmQ4N2Q5NjZmNGY3In0.eyJhdWQiOiIxIiwianRpIjoiNmI2ZTRlNmNjMzA5MzNhZTU3YjNmNmYzZjE2ZDVkOGM5MDA4Y2UzZmYxYjJkN2VhZWUzYjcyOGM2MjYwZThhMzAyMDlmZDg3ZDk2NmY0ZjciLCJpYXQiOjE1OTk2NjEwNjMsIm5iZiI6MTU5OTY2MTA2MywiZXhwIjoxNjMxMTk3MDYzLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.S1HNmRfTTALGhgmdMYSOzrJHCbCFlYsW__f_HxCE1bR8Ap4kPbq6oZ2kxb8mlOLG-5yjNpLL6fZSc59CP_hdWNbSSfM5_MBdV2u6okVoG9NayxXL_OKiIVibrRu5MjLK4IIR9TbZibYui4tTaAGup3FT6mIAPxaDYtJ8fgbLLnLw-H4zLNo2kyfvnZtyWGzQQF7QlHlJpOl5sYtBWKhggz3inGbVyekh63W5yjJnReajNxjJpibplGU3bdUl8KWvM3oXhmo1uzimAcjE5vPzE3X8Vyk8x5ta9Vrq3wu5rcQ7ZAlodwFubZWb3A8BwMvccUyAGua-uLd2PTLRzNcryzpXZD5z7W5ZKwErh7d-npjMTLGRVeKmH7Z3QUwInjLuK1DKT0XWaRtXAMEnJMd0Y1V-vkCXI2NmDldJl6IfzsaN9ia9QdY5pnCmCY8N3S6G2XnfFN6MnGntO6scuJUhyoKsQf2nmQ7cOl-l2lfFxhSQ7skKR76HNnF9-SxWqrqs5JHoDbSE9W0lzctIPJ9jHIGqIsPjRLlGg15377ox2Dh4gtTgcVJl1VO6uz9qZWZUc6mPl7ISYaFUniI0P43a7DQLl8bpqoZ5mAbWu7p3bcXkMd_VwaaloyCtiBr81qTzfy2RqKDHHFrF3tcFCH4DHBottUXkQIA_frKNy4fyAss";
            // axios.post('/api/categories/upsert');
            // if(this.id){
            //     axios.get('/api/categories/' + this.id)
            //         .then(res => this.item = res.data);
            // }
        },
        mounted() {
        },
        beforeRouteLeave(to, from, next) {
            this.category = newCategory(this.categories.length)
            next()
        },
        methods: {
            removeCategory(index) {
                if(confirm('Are you sure?')){
                    this.$store.dispatch('removeCategory', index)
                }
            },
            addCategory() {
                if(!this.categories.length || this.categories[this.categories.length-1].id > 0) {
                    this.$store.commit('ADD_CATEGORY', newCategory(this.categories.length))
                }
                this.$nextTick(() => {
                    window.scrollTo(0, document.body.scrollHeight)
                    this.$refs[''][0].focus()
                });
            },
            saveCategories() {
                this.errors = '';
                this.$store.dispatch('saveCategories')
                    .catch((error) => {
                        let messages = Object.values(error.response.data.errors)
                        let errors = [].concat.apply([], messages)
                        for(let i=0; i < errors.length; i++) {
                            errors[i] = errors[i].replace(/(\d)/, function(n){return ++n;})
                        }
                        this.errors = errors
                    });
            },
            update($event, property, index) {
                this.$store.commit('UPDATE_CATEGORY', {
                    index,
                    property,
                    value: $event.filename?$event.filename:$event.target.value
                })
            },
            previewTemplate() {
                return `<div class="dz-preview dz-file-preview">
                            <div class="dz-image">
                                <img data-dz-thumbnail />
                            </div>
                            <div class="dz-details">
                            </div>
                            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                            <div class="dz-success-mark"><span>✔</span></div>
                            <div class="dz-error-mark"><span>✘</span></div>
                            <div class="dz-error-message"><span data-dz-errormessage></span></div>
                        </div>`;
            },
            removedfile(file, index) {
                axios.post('/api/delete-image', {
                        image: file.filename,
                    })
                    .then(res => {
                        // let index = this.categories.findIndex(category => category.image == file.filename)
                        this.categories[index].image = ""
                        this.saveCategories()
                        // file.previewElement.remove();
                    })
                    .catch(error => {
                        alert(error)
                        console.log(error)
                        // let messages = Object.values(error.response.data.errors)
                        // this.errors = [].concat.apply([], messages)
                    });
                    // this.item.images = this.item.images.filter(item => item !== file.filename)
            },
            manualImageUpload(index, image) {
                if(image) {
                    let extension = image.match(/[\w]*$/i)
                    let url = '/storage/images/'+image;
                    let file = {
                        size: '',
                        filename: image,
                        type: "image/"+extension, 
                        // accepted: true
                    };
                    this.$refs.dropzone[index].manuallyAddFile(file, url);
                }
            },
        }
    }
</script>

<style scoped>
    img{
        vertical-align: middle;
    }
    hr{
        margin-bottom: 30px;
    }
</style>