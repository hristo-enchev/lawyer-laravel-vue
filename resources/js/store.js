import { isLoggedIn, logOut } from './shared/utils/auth';
import router from './routes';

export default {
    state: {
        lastCheck: {
            from: null,
            to: null
        },
        isLoggedIn: false,
        user: {}
    },
    mutations: {
        setLastCheck(state, payload) {
            state.lastCheck = payload;
        },
        setUser(state, payload) {
            state.user = payload;
        },
        setLoggedIn(state, payload) {
            state.isLoggedIn = payload;
        }
    },
    actions: {
        setLastCheck(context, payload) {
            context.commit('setLastCheck', payload);
            localStorage.setItem('lastChech', JSON.stringify(payload));
        },
        loadLastCheck(context) {
            const lastCheck = localStorage.getItem('lastChech');
            if (lastCheck) {
                context.commit('setLastCheck', JSON.parse(lastCheck));
            }

            context.commit('setLoggedIn', isLoggedIn());
        },
        async loadUser({ commit, dispatch }) {
            if (isLoggedIn()) {
                try {
                    const user = (await axios.get('/api/user')).data;
                    
                    commit('setUser', user);
                    commit('setLoggedIn', true);
                } catch (error) {
                    dispatch('logout');
                }
            }
        },
        logout({ commit }) {
            commit('setUser', {});
            commit('setLoggedIn', false);

            logOut();
        }
    }
}