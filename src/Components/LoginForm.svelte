<script>
	// your script goes here

	let login = "";
	let password = "";

	export let reason;

	export let clickHandler;

	export let title_text;
	export let footer_text;
	export let reason_text;

	export let link;

	/**
	 *
	 * @param e {Event & {currentTarget: EventTarget & HTMLFormElement;}}
	 */
	async function click_handler(e) {
		e.preventDefault();
		clickHandler(login, password);
	}
</script>

<section class="text-gray-400 bg-gray-900 body-font">
	<div
		class="container px-5 py-24 mx-auto flex flex-wrap items-center flex-col"
	>
		<div class="mx-auto text-red-600 text-center mb-10 h-5">
			{#if reason}
				{reason_text}
			{/if}
		</div>
		<form
			on:submit={click_handler}
			class="lg:w-2/6 md:w-1/2 bg-gray-800 bg-opacity-50 rounded-lg p-8 flex flex-col md:ml-auto md:mr-auto w-full mt-10 md:mt-0"
		>
			<h2 class="text-white text-lg font-medium title-font mb-5">
				{title_text}
			</h2>
			<div class="relative mb-4">
				<label for="login" class="leading-7 text-sm text-gray-400">Login</label>
				<input
					type="text"
					id="login"
					bind:value={login}
					class="w-full bg-gray-600 bg-opacity-20 focus:bg-transparent focus:ring-2 focus:ring-indigo-900 rounded border border-gray-600 focus:border-indigo-500 text-base outline-none text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
				/>
			</div>
			<div class="relative mb-4">
				<label for="password" class="leading-7 text-sm text-gray-400"
					>Password</label
				>
				<input
					type="password"
					id="password"
					bind:value={password}
					class="w-full bg-gray-600 bg-opacity-20 focus:bg-transparent focus:ring-2 focus:ring-indigo-900 rounded border border-gray-600 focus:border-indigo-500 text-base outline-none text-gray-100 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
				/>
			</div>
			<button
				class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg"
				type="submit">Zatwierdź</button
			>
			<a href={`./#/${link}`} class="text-white visited:text-white">
				{footer_text}</a
			>
		</form>
	</div>
</section>

<style>
	/* your styles go here */
</style>
