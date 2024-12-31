
import './bootstrap';

import { createApp } from 'vue';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import pinia from '@/store';

const app = createApp({});
app.use(pinia);
app.use(VueSweetalert2);


/////////////////////////////// DEFAULT ////////////////////////////////////////
import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

import Loading from './components/default/loading/Loading.vue';
app.component('loading-component', Loading);

import LoadingFull from './components/default/loading/LoadingFull.vue';
app.component('loading-full-component', LoadingFull);

import AlertCard from './components/default/alerts/AlertCard.vue';
app.component('alert-card', AlertCard);

import AlertLoading from './components/default/alerts/AlertLoading.vue';
app.component('alert-loading', AlertLoading);

import ChordsListWrap from './components/default/chord/ChordsListWrap.vue';
app.component('chords-list-wrap', ChordsListWrap);

////////////////////////////// APP /////////////////////////////////////////////
import ChordCreate from './components/music-app/ChordCreate.vue';
app.component('chord-create', ChordCreate);

import Singer from './components/music-app/Singer.vue';
app.component('singer', Singer);

import SingerCreate from './components/music-app/SingerCreate.vue';
app.component('singer-create', SingerCreate);

import SingerUpdate from './components/music-app/SingerUpdate.vue';
app.component('singer-update', SingerUpdate);

import Tone from './components/music-app/Tone.vue';
app.component('tone', Tone);

import ToneCreate from './components/music-app/ToneCreate.vue';
app.component('tone-create', ToneCreate);

import ToneUpdate from './components/music-app/ToneUpdate.vue';
app.component('tone-update', ToneUpdate);

import Rhythm from './components/music-app/Rhythm.vue';
app.component('rhythm', Rhythm);

import RhythmCreate from './components/music-app/RhythmCreate.vue';
app.component('rhythm-create', RhythmCreate);

import RhythmUpdate from './components/music-app/RhythmUpdate.vue';
app.component('rhythm-update', RhythmUpdate);

import MusicCreate from './components/music-app/MusicCreate.vue';
app.component('music-create', MusicCreate);



import ShowSingerMusics from './components/music-app/ShowSingerMusics.vue';
app.component('show-singer-musics', ShowSingerMusics);

import ShowSingerMusic from './components/music-app/ShowSingerMusic.vue';
app.component('show-singer-music', ShowSingerMusic);


/////////////////////////// WEB ////////////////////////////////////////
import Home from './components/web/Home.vue';
app.component('home-list', Home);

import ShowMusic from './components/web/ShowMusic.vue';
app.component('show-music', ShowMusic);

app.mount('#app');