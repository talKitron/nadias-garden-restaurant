import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        categories: [],
        items: [],
        feedback: ''
    },
    mutations: {
        SET_CATEGORIES(state, categories) {
            state.categories = categories
        },
        ADD_CATEGORY(state, category) {
            state.categories.push(category)
        },
        REMOVE_CATEGORY(state, index) {
            state.categories.splice(index, 1)
        },
        UPDATE_CATEGORY(state, {index, property, value}) {
            state.categories[index][property] = value
        },
        SET_FEEDBACK(state, feedback) {
            state.feedback = feedback
        },
        SET_ITEMS(state, items) {
            state.items = items
        },
        ADD_ITEM(state, item) {
            state.items.push(item)
        },
        REMOVE_ITEM(state, index) {
            state.items.splice(index, 1)
        },
        /*UPDATE_ITEM(state, {index, property, value}) {
            // let index = state.items.findIndex(item => item.id == id)
            state.items[index][property] = value
        },*/
    },
    actions: {
        removeCategory({commit, state}, index) {
            let id = state.categories[index].id
            if(id > 0){
                return axios.delete('/api/categories/' + id)
                    .then((res) => commit('REMOVE_CATEGORY', index))
            }
            commit('REMOVE_CATEGORY', index);
        },
        removeItem({commit, state, getters}, id) {
            return new Promise((resolve, reject) => {
                axios.delete('/api/menu-items/' + id)
                .then((res) => {
                    // let index = state.items.findIndex(item => item.id == id)
                    let index = getters.itemIndex(id)
                    commit('REMOVE_ITEM', index)
                    resolve()
                })
            })
            .catch(error => {
                reject(error)
            });
        },
        saveCategories({commit, state}) {
            return new Promise((resolve, reject) => {
                axios.post('/api/categories/upsert', {
                    categories: state.categories
                })
                .then((res) => {
                    if(res.data.success){
                        commit('SET_FEEDBACK', 'Changes saved successfuly.')
                        setTimeout(() => commit('SET_FEEDBACK', ''), 3000)
                        commit('SET_CATEGORIES', res.data.categories)
                        resolve()
                    }
                })
                .catch(error => {
                    reject(error)
                });
            });
        },
        saveItems({commit, state}, payload) {
            commit('ADD_ITEM', payload.item)
            return new Promise((resolve, reject) => {
                axios.post(payload.url, payload.item)
                    .then(res => {
                        if(res.data.success){
                            commit('SET_FEEDBACK', 'Changes saved successfuly.')
                            setTimeout(() => commit('SET_FEEDBACK', ''), 3000)
                            commit('SET_ITEMS', res.data.items)
                            setTimeout(() => resolve(), 100)
                        }
                    })
                    .catch(error => {
                        reject(error)
                    });
            });
        },
        removeImage({commit, state, getters}, payload) {
            return new Promise((resolve, reject) => {
                axios.post('/api/menu-items/delete-image', {
                        menu_item_id: payload.item.id, 
                        image: payload.image
                    })
                    .then(res => {
                        let index = payload.item.images.findIndex(image => image == payload.image)
                        payload.item.images.splice(index, 1)
                        
                        commit('SET_FEEDBACK', 'Changes saved successfuly.')
                        setTimeout(() => commit('SET_FEEDBACK', ''), 3000)
                        
                        resolve()
                    })
                    .catch(error => {
                        reject(error)
                    });
            });
        },
    },
    getters: {
        item: (state) => (id) => {
            return state.items.filter(item => item.id === Number(id))[0]
        },
        itemIndex: (state) => (id) => {
            return state.items.findIndex(item => item.id == id)
        },
        itemsPerCategory: (state) => (categoryId) => {
            return state.items.filter(item => item.category_id === categoryId)
        }
    }
});