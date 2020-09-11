import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        categories: [],
        items: {},
        feedback: ''
    },
    mutations: {
        SET_CATEGORIES(state, categories) {
            state.categories = categories;
        },
        ADD_CATEGORY(state, categories) {
            state.categories.push(categories);
        },
        REMOVE_CATEGORY(state, index) {
            state.categories.splice(index, 1);
        },
        UPDATE_CATEGORY(state, {index, property, value}) {
            state.categories[index][property] = value;
        },
        SET_FEEDBACK(state, feedback) {
            state.feedback = feedback;
        },
    },
    actions: {
        removeCategory({commit, state}, index) {
            let id = state.categories[index].id;
            if(id > 0){
                return axios.delete('/api/categories/' + id)
                    .then((res) => commit('REMOVE_CATEGORY', index));
            }
            commit('REMOVE_CATEGORY', index);
        },
        saveCategories({commit, state}) {
            axios.post('/api/categories/upsert', {
                categories: state.categories
            })
            .then((res) => {
                if(res.data.success){
                    commit('SET_FEEDBACK', 'Changed saved successfuly.');
                    setTimeout(() => commit('SET_FEEDBACK', ''), 3000)
                    commit('SET_CATEGORIES', res.data.categories)
                }
            })
        },
    }
});