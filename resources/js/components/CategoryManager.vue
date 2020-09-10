<template>
    <form @submit.prevent="saveCategories">
        <a @click="addCategory" class="add">+ Add category</a>
        <div v-for="(category, index) in categories" :key="category.id">
            <input type="text" v-model="category.name" :ref="category.name">
            <input type="number" v-model="category.display_order">
            <a @click="removeCategory(index)" class="remove">delete</a>
            <div>
                <img v-if="category.image" :src="`/images/${category.image}`" width="100">
                <label v-else>Image: </label>
                <input type="text" v-model.lazy="category.image">
            </div>
            <hr>
        </div>
        <button type="submit">Save</button>
        <div>{{ feedback }}</div>
    </form>
</template>

<script>
    export default {
        props: ['initialCategories'],
        data() {
            return {
                categories: _.cloneDeep(this.initialCategories),
                feedback: ''
            };
        },
        // created() {
            // axios.defaults.headers.common["Authorization"] = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjZiNmU0ZTZjYzMwOTMzYWU1N2IzZjZmM2YxNmQ1ZDhjOTAwOGNlM2ZmMWIyZDdlYWVlM2I3MjhjNjI2MGU4YTMwMjA5ZmQ4N2Q5NjZmNGY3In0.eyJhdWQiOiIxIiwianRpIjoiNmI2ZTRlNmNjMzA5MzNhZTU3YjNmNmYzZjE2ZDVkOGM5MDA4Y2UzZmYxYjJkN2VhZWUzYjcyOGM2MjYwZThhMzAyMDlmZDg3ZDk2NmY0ZjciLCJpYXQiOjE1OTk2NjEwNjMsIm5iZiI6MTU5OTY2MTA2MywiZXhwIjoxNjMxMTk3MDYzLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.S1HNmRfTTALGhgmdMYSOzrJHCbCFlYsW__f_HxCE1bR8Ap4kPbq6oZ2kxb8mlOLG-5yjNpLL6fZSc59CP_hdWNbSSfM5_MBdV2u6okVoG9NayxXL_OKiIVibrRu5MjLK4IIR9TbZibYui4tTaAGup3FT6mIAPxaDYtJ8fgbLLnLw-H4zLNo2kyfvnZtyWGzQQF7QlHlJpOl5sYtBWKhggz3inGbVyekh63W5yjJnReajNxjJpibplGU3bdUl8KWvM3oXhmo1uzimAcjE5vPzE3X8Vyk8x5ta9Vrq3wu5rcQ7ZAlodwFubZWb3A8BwMvccUyAGua-uLd2PTLRzNcryzpXZD5z7W5ZKwErh7d-npjMTLGRVeKmH7Z3QUwInjLuK1DKT0XWaRtXAMEnJMd0Y1V-vkCXI2NmDldJl6IfzsaN9ia9QdY5pnCmCY8N3S6G2XnfFN6MnGntO6scuJUhyoKsQf2nmQ7cOl-l2lfFxhSQ7skKR76HNnF9-SxWqrqs5JHoDbSE9W0lzctIPJ9jHIGqIsPjRLlGg15377ox2Dh4gtTgcVJl1VO6uz9qZWZUc6mPl7ISYaFUniI0P43a7DQLl8bpqoZ5mAbWu7p3bcXkMd_VwaaloyCtiBr81qTzfy2RqKDHHFrF3tcFCH4DHBottUXkQIA_frKNy4fyAss";
            // axios.post('/api/categories/upsert');
        // },
        methods: {
            removeCategory(index) {
                if(confirm('Are you sure?')){
                    let id = this.categories[index].id;
                    if(id > 0){
                        axios.delete('/api/categories/' + id);
                    }
                    this.categories.splice(index, 1);
                }
            },
            addCategory() {
                this.categories.push({
                    id: 0,
                    name: '',
                    image: '',
                    display_order: this.categories.length + 1
                });
                this.$nextTick(() => {
                    window.scrollTo(0, document.body.scrollHeight);
                    this.$refs[''][0].focus();
                })
            },
            saveCategories() {
                axios.post('/api/categories/upsert', {
                    categories: this.categories
                })
                .then((res) => {
                    if(res.data.success){
                        this.feedback = 'Changed saved successfuly.';
                        this.categories = res.data.categories;
                    }
                })
            }
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