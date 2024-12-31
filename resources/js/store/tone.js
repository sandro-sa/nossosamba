import { defineStore } from "pinia";
import axios from "axios";

export const useTone = defineStore('tone',{
    
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