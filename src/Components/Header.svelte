<script>
	// your script goes here
	import { get } from 'svelte/store';
	import {userInfo, userUpdate} from "../config/userInfo";

	let userInfo1;	

	userInfo.subscribe(el=>{
		userInfo1 = el
	})


	async function logout(){
		await fetch("./php/log_out.php");
		window.location.href ="./#/"
		userUpdate();
	}
</script>

<header class="text-gray-400 bg-gray-900 body-font">
	<div
		class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center"
	>
		<a
			class="flex title-font font-medium items-center text-white mb-4 md:mb-0 visited:text-white"
			href="./#/"
		>
			<svg
				xmlns="http://www.w3.org/2000/svg"
				fill="none"
				stroke="currentColor"
				stroke-linecap="round"
				stroke-linejoin="round"
				stroke-width="2"
				class="w-10 h-10 text-white p-2 bg-indigo-500 rounded-full"
				viewBox="0 0 24 24"
			>
				<path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
			</svg>
			<span class="ml-3 text-xl">Wypożyczalnia</span>
		</a>
		<nav
			class="md:ml-auto flex flex-wrap items-center text-base justify-center"
		>
			{#if userInfo1.logged == "admin" || userInfo1.logged == "moderator"}
				<a
					class="mr-5 hover:text-white visited:text-gray-400"
					href="./#/applications"
				>
					Zgłoszenia
				</a>
				<a
					class="mr-5 hover:text-white visited:text-gray-400"
					href="./#/sheduler"
				>
					Sheduler
				</a>
			{/if}
			{#if userInfo1.logged == "admin"}
				<a class="mr-5 hover:text-white visited:text-gray-400" href="./#/users">
					Użytkownicy
				</a>
			{/if}
			{#if userInfo1.logged != null}
				<a class="mr-5 hover:text-white visited:text-gray-400" href="./#/offers">
					Dostępne oferty
				</a>
				<a class="mr-5 hover:text-white visited:text-gray-400" href="./#/your_orders">
					Twoje zamówienia
				</a>
			{/if}
			{#if userInfo1.logged == null}
				<a class="mr-5 hover:text-white visited:text-gray-400" href="./#/login">
					Login
				</a>
				{:else}
				<!-- svelte-ignore a11y-missing-attribute -->
				<span class="inline-block py-1 px-2 rounded bg-gray-800 text-gray-400 text-opacity-75 text-xs font-medium tracking-widest mr-5">{userInfo1.login}</span>
				<!-- svelte-ignore a11y-missing-attribute -->
				<a class="mr-5 hover:text-white visited:text-gray-400 cursor-pointer" on:click={logout}>
					Wyloguj
				</a>
			{/if}
		</nav>
	</div>
</header>