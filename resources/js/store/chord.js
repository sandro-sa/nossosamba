import { defineStore } from "pinia";
import axios from "axios";

// axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

export const useChord = defineStore('chord',{
    
    state: () => ({}),
    actions: {
        get(url, config){
            return axios.get(url, config)   
        },
        insert(url, data, config){
            return axios.post(url, data, config)
        },
        update(url, data, config){
            return axios.post(url, data, config)
        },
        delete(url, data, config){
            return  axios.post(url, data, config)
        }
    }
    
});