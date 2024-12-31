import axios from 'axios';

axios.defaults.baseURL = import.meta.env.APP_URL;
axios.defaults.withCredentials = true;