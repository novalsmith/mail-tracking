import axios from 'axios';
import router from '../router'

const setAuthHeader = () => {
    axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
}

const handleUnauthorized = async (error) => {
    if (error.response.status === 401) {
        router.push('/logout')
    } else {
        return Promise.reject(error);
    }
}

axios.interceptors.response.use(resp => resp, handleUnauthorized);
setAuthHeader();

export default axios;