<template>
    <div class="menu-item-list">
        <select v-model="categoryId" @change="fetchItems" required>
            <option value="">Select a category</option>
            <option v-for="cat in categories" :value="cat.id" :key="cat.id">{{cat.name}}</option>
        </select>
        <ul>
            <li v-for="item in itemsPerCategory" :key="item.id">
                <router-link :to="{name: 'edit-item', params: {id: item.id}}">
                    {{item.name}}
                </router-link>
            </li>
        </ul>
        <span>{{ feedback }}</span>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        data() {
            return {
                categoryId: this.$store.state.categories.length ? this.$store.state.categories[0].id : null,
                items: []
            };
        },
        computed: mapState({
            categories: 'categories',
            // itemsPerCategory: 'itemsPerCategory',
            itemsPerCategory () {
                return this.$store.getters.itemsPerCategory(this.categoryId)
            },
            feedback() {
                return this.$store.state.feedback;
            }
        }),
        created() {
            // let items = this.$store.state.items.find(item => item.category_id === 1);
            // console.log(items)
            // this.fetchItems();
        },
        methods: {
            fetchItems() {
                // let itemsPerCategory = this.$store.state.items.filter(item => item.category_id === this.categoryId)
                // console.log(categories)
                //query().where('category_id', this.categoryId).get()
                // this.items = itemsPerCategory

                // axios.get(`/api/categories/${this.categoryId}/items`)
                //     .then(res => this.items = res.data);
            }
        }
    }
</script>