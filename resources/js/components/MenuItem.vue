<template>
    <form class="item-form" @submit.prevent="saveItem">
        <div :key="item.id">
            <div>
                <input type="text" placeholder="Item name" v-model="item.name" required>
                €<input type="number" min="0" step=".01" v-model="item.price" required>
                <a v-if="this.item.id" @click="removeItem(item.id)" class="remove">delete</a>
            </div>
            <div>
                <textarea v-model="item.description" placeholder="Item description" required></textarea>
            </div>
            <div>
                <select v-model="item.category_id" required>
                    <option value="">Select a category</option>
                    <option v-for="cat in categories" :value="cat.id" :key="cat.id">{{cat.name}}</option>
                </select>
            </div>
            <!-- <img v-if="id && item.image" :src="`/storage/images/${item.image}`" width="200"/> -->
            <drop-zone 
                id="dz" 
                :options="dropzoneOptions" 
                :destroyDropzone="false"
                v-on:vdropzone-removed-file="removedfile" 
                ref="dropzone"
            />
            {{/* :include-styling="true" v-on:vdropzone-thumbnail="thumbnail"  */}}
        </div>
        <button type="submit">Save</button>
        <div>{{ feedback }}</div>
        <ul>
            <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
        </ul>
    </form>
</template>

<script>
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    import {mapState} from 'vuex'

    function newItem() {
        return {
            id: 0,
            name: '',
            price: 0.00,
            // image: '',
            images: [],
            category_id: '',
            description: ''
        };
    }

    export default {
        components: {
            dropZone: vue2Dropzone
        },
        props: ['id'],
        data() {
            return {
                dropzoneOptions: {
                    url: '/api/add-image',
                    thumbnailWidth: 200,
                    maxFiles: 3,
                    acceptedFiles: 'image/*',
                    addRemoveLinks: true,
                    previewTemplate: this.previewTemplate(),
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                    },
                    success(file, res){
                        file.filename = res;
                    },
                },
                item: newItem(),
                errors: [],
            };
        },
        computed: mapState({
            categories: 'categories',
            items: 'items',
            feedback() {
                return this.$store.state.feedback;
            }
        }),
        created() {
            if(this.id) {
                this.getItem(this.id)
            }
        },
        mounted: function() {
            this.item.images.forEach((menuItemImage, i) => {
                this.manualImageUpload(menuItemImage.image)
            });
        },
        beforeRouteLeave(to, from, next) {
            this.item = newItem()
            next()
        },
        methods: {
            getItem(id) {
                // this.item = this.$store.getters.item(id)
                this.index = this.$store.getters.itemIndex(id)
                this.item = this.$store.state.items[this.index]
            },
            removeItem(id) {
                if(confirm('Are you sure?')){
                    this.$store.dispatch('removeItem', id)
                        .then(this.$router.push('/items'))
                }
            },
            saveItem() {
                let files = this.$refs.dropzone.getAcceptedFiles()
                // if(files.length > 0 && files[0].filename){
                //     this.item.image = files[0].filename
                // }
                let images = files.map((file) => {return file.filename})
                this.item.images = images
                let url = this.id ? '/api/menu-items/' + this.id : '/api/menu-items/add'
                this.$store.dispatch('saveItems', {
                        url: url,
                        item: this.item
                    })
                    .then(() => {
                        this.$router.push('/items')
                    })
                    .catch(error => {
                        let messages = Object.values(error.response.data.errors)
                        this.errors = [].concat.apply([], messages)
                    });
                // })
                // axios.post(url, this.item)
                //     .then(res => {
                //         this.$router.push('/')
                //     })
                //     .catch(error => {
                //         let messages = Object.values(error.response.data.errors)
                //         this.errors = [].concat.apply([], messages)
            },
            /*update($event, property, index) {
                this.$store.commit('UPDATE_ITEM', {
                    index,
                    property,
                    value: $event.filename?$event.filename:$event.target.value
                })
            },*/
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
            /*thumbnail(file, dataUrl) {
                var j, len, ref, thumbnailElement;
                if (file.previewElement) {
                    file.previewElement.classList.remove("dz-file-preview");
                    ref = file.previewElement.querySelectorAll("[data-dz-thumbnail-bg]");
                    for (j = 0, len = ref.length; j < len; j++) {
                        thumbnailElement = ref[j];
                        thumbnailElement.alt = file.name;
                        thumbnailElement.style.backgroundImage = 'url("' + dataUrl + '")';
                    }
                    return setTimeout(((function(_this) {
                        return function() {
                            return file.previewElement.classList.add("dz-image-preview");
                        };
                    })(this)), 1);
                }
            },*/
            removedfile(file) {
                this.$store.dispatch('removeImage', {
                        item: this.item, 
                        image: file.filename,
                        url: '/api/menu-items/delete-image'
                    })
                    .then(() => {
                        file.previewElement.remove();
                    })
                    .catch(error => {
                        console.log(error)
                        // let messages = Object.values(error.response.data.errors)
                        // this.errors = [].concat.apply([], messages)
                    });
                    // this.item.images = this.item.images.filter(item => item !== file.filename)
            },
            manualImageUpload(image) {
                let extension = image.match(/[\w]*$/i)
                let url = '/storage/images/'+image;
                // let base64img = this.getBase64Image(url, extension)
                let file = {
                    size: '',
                    filename: image,
                    // dataURL: base64img,
                    // name: i+1, 
                    type: "image/"+extension, 
                    // accepted: true
                };
                this.$refs.dropzone.manuallyAddFile(file, url);
            },
            /*getBase64Image(imgUrl, extension) {
                let img = document.createElement('img'); 
                img.src = imgUrl;
                let canvas = document.createElement("canvas");
                canvas.width = img.width;
                canvas.height = img.height;
                let ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0);
                let dataURL = canvas.toDataURL("image/png");
                return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
            }*/
        },
    }
</script>
<style scoped>
/**
    #dz {
        background-color: orange;
        font-family: 'Arial', sans-serif;
        letter-spacing: 0.2px;
        color: #777;
        transition: background-color .2s linear;
        height: 200px;
        padding: 40px;
    }

    #dz .dz-preview {
        width: 160px;
        display: inline-block;
        background: none;
    }
    #dz .dz-preview .dz-image {
        width: 80px;
        height: 80px;
        margin-left: 40px;
        margin-bottom: 10px;
    }
    #dz .dz-image > div {
        width: inherit;
        height: inherit;
        border-radius: 50%;
        background-size: contain;
    }
    #dz .dz-preview .dz-image > img {
        width: 100%;
    }

    #dz .dz-preview .dz-details {
        color: white;
        transition: opacity .2s linear;
        text-align: center;
    }
    #dz .dz-success-mark, .dz-error-mark, .dz-remove {
        display: none;
    }
*/
</style>