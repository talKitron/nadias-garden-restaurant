<template>
    <form class="item-form" @submit.prevent="saveItem" novalidate>
        <div :key="item.id">
            <div>
                <input type="text" placeholder="Item name" :value="item.name" @input="update($event, 'name', id)" required>
                €<input type="number" min="0" step=".01" :value="item.price" @input="update($event, 'price', id)" required>
                <a v-if="this.item.id" @click="removeItem(item.id)" class="remove">delete</a>
            </div>
            <div>
                <textarea :value="item.description" placeholder="Item description" @input="update($event, 'description', id)" required></textarea>
            </div>
            <div>
                <select :value="item.category_id" @input="update($event, 'category_id', id)" required>
                    <option value="">Select a category</option>
                    <option v-for="cat in categories" :value="cat.id" :key="cat.id">{{cat.name}}</option>
                </select>
            </div>
            <img v-if="id && item.image" :src="`/storage/images/${item.image}`" width="200"/>
            <drop-zone :options="dropzoneOptions" id="dz" v-on:vdropzone-success="update($event, 'image', id)" ref="dropzone"></drop-zone>
        </div>
        <button type="submit">Save</button>
        <div>{{ feedback }}</div>
        <ul>
            <li v-for="(error, index) in errors" :key="index">{{error}}</li>
        </ul>
    </form>
</template>

<script>
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    import {mapState} from 'vuex'

    function newItem() {
        return {
            name: '',
            price: 0.00,
            image: '',
            category_id: '',
            description: ''
        }
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
                    acceptedFiles: 'image/*',
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                    },
                    success(file, res){
                        file.filename = res
                    }
                },
                item: newItem(),
                errors: []
            };
        },
        computed: mapState({
            categories: 'categories',
            items: 'items',
            feedback() {
                return this.$store.state.feedback;
            }
        }),
        beforeCreate() {
            
        },
        created() {
            let item
            if(this.id && (item = this.$store.getters.item(this.id)) != undefined){
                this.item = item
                // axios.get('/api/menu-items/' + this.id)
                //     .then(res => this.item = res.data)
            } else {
                this.$store.commit('ADD_ITEM', this.item)
            }
        },
        beforeRouteLeave(to, from, next) {
            this.item = newItem()
            next()
        },
        methods: {
            removeItem(id) {
                if(confirm('Are you sure?')){
                    this.$store.dispatch('removeItem', id)
                        .then(this.$router.push('/'))
                }
            },
            saveItem() {
                let files = this.$refs.dropzone.getAcceptedFiles()
                if(files.length > 0 && files[0].filename){
                    this.item.image = files[0].filename
                }
                let url = this.id ? '/api/menu-items/' + this.id : '/api/menu-items/add'
                console.log(this.item)
                this.$store.dispatch('saveItems', {
                        url: url,
                        item: this.item
                    })
                    .then(() => {
                        this.$router.push('/')
                    })
                    .catch(error => {
                        let messages = Object.values(error.response.data.errors)
                        this.errors = [].concat.apply([], messages)
                    });
                // axios.post(url, this.item)
                //     .then(res => {
                //         this.$router.push('/')
                //     })
                //     .catch(error => {
                //         let messages = Object.values(error.response.data.errors)
                //         this.errors = [].concat.apply([], messages)
                //     });
            },
            update($event, property, id) {
                this.$store.commit('UPDATE_ITEM', {
                    id,
                    property,
                    value: $event.filename?$event.filename:$event.target.value
                })
            }
        }
    }
</script>