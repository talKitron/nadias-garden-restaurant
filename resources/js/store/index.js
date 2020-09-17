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
        ADD_CATEGORY(state, categories) {
            state.categories.push(categories)
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
        ADD_ITEM(state, items) {
            state.items.push(items)
        },
        REMOVE_ITEM(state, index) {
            index = state.items.findIndex(item => item.id == id)
            state.items.splice(index, 1)
        },
        UPDATE_ITEM(state, {id, property, value}) {
            let index = state.items.findIndex(item => item.id == id)
            state.items[index][property] = value
        },
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
        removeItem({commit, state}, id) {
            return axios.delete('/api/menu-items/' + id)
                .then((res) => {
                    let index = state.items.findIndex(item => item.id == id)
                    commit('REMOVE_ITEM', index)
                    // commit('REMOVE_ITEM', index);
                })
        },
        saveCategories({commit, state}) {
            return new Promise((resolve, reject) => {
                axios.post('/api/categories/upsert', {
                    categories: state.categories
                })
                .then((res) => {
                    if(res.data.message === "success"){
                        commit('SET_FEEDBACK', 'Changes saved successfuly.')
                        setTimeout(() => commit('SET_FEEDBACK', ''), 3000)
                        commit('SET_CATEGORIES', res.data.categories)
                    }
                })
                .catch(error => {
                    reject(error)
                });
            });
        },
        saveItems({commit, state}, payload) {
            return new Promise((resolve, reject) => {
                axios.post(payload.url, payload.item)
                    .then(res => {
                        if(res.data.success){
                            commit('SET_FEEDBACK', 'Changes saved successfuly.')
                            setTimeout(() => commit('SET_FEEDBACK', ''), 3000)
                            commit('SET_ITEMS', res.data.items)
                            resolve()
                        }
                    })
                .catch(error => {
                    reject(error)
                });
            });
        },
    },
    getters: {
        item: (state) => (id) => {
          return state.items.filter(p => p.id === Number(id))[0]
        }
    }
});