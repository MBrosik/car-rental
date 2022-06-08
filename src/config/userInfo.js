import { writable } from 'svelte/store';

export let userInfo = writable({
   /** @type {null|"admin"|"moderator"|"user"}*/
   logged: null,
   /** @type {null|string}*/
   login: null,
});

export async function userUpdate() {
   let res = await fetch("./php/userInfo.php");
   /**@type {{status:null|"admin"|"moderator"|"user", login: null|string}}*/
   let data = await res.json();

   userInfo.update(()=>({logged: data.status, login: data.login}))    
};

userUpdate();