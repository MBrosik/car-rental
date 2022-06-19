<script>
	import Default from "../../../DEFAULT.svelte";


	/**@type {boolean}*/
	let switchBool;

	/**@type {number}*/
	let timestamp;

	async function getData() {
		let res = await fetch("./php/moder/sheduler/getDateStatus.php");

		/**@type {{time_difference:number, sheduler_active:boolean}}*/
		let data = await res.json();

		switchBool = data.sheduler_active;
		timestamp = data.time_difference;
	}

	let awaitData = getData();

	async function updateData() {
		let form = new FormData();

		form.append("switchBool", switchBool);
		form.append("timestamp", timestamp);

		let res = await fetch("./php/moder/sheduler/updateDatesheduler.php", {
			method: "POST",
			body: form,
		});		
	}
</script>

<main class=" w-1/2 m-auto mt-5">	

	{#await awaitData}
		<h2 class="text-white text-center m-20 text-5xl">Loading</h2>
	{:then}
		<div
			class="p-5 w-max mx-auto rounded-lg border-solid border-8 border-gray-700"
		>
			<h1 class="text-gray-200 text-center">Server Clock:</h1>

			<div class="flex justify-center items-center mt-5">
				<label class="switch">
					<input type="checkbox" bind:checked={switchBool} />
					<span class="slider round" />
				</label>
			</div>

			<h1 class="text-gray-200 text-center mt-10 mb-4">timestamp</h1>

			<input
				type="number"
				bind:value={timestamp}
				class="bg-transparent text-gray-200 w-full"
			/>
			<div class="flex justify-center items-center mt-5">
				<button
					class="text-white bg-green-500 border-0 py-2 px-8 focus:outline-none hover:bg-green-600 rounded text-lg"
					on:click={updateData}>Submit</button
				>
			</div>
		</div>
	{:catch err}
		{err}
		<Default />
	{/await}
</main>

<style>
	.switch {
		position: relative;
		display: inline-block;
		width: 60px;
		height: 34px;
	}

	.switch input {
		opacity: 0;
		width: 0;
		height: 0;
	}

	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #ccc;
		-webkit-transition: 0.4s;
		transition: 0.4s;
	}

	.slider:before {
		position: absolute;
		content: "";
		height: 26px;
		width: 26px;
		left: 4px;
		bottom: 4px;
		background-color: white;
		-webkit-transition: 0.4s;
		transition: 0.4s;
	}

	input:checked + .slider {
		background-color: #2196f3;
	}

	input:focus + .slider {
		box-shadow: 0 0 1px #2196f3;
	}

	input:checked + .slider:before {
		-webkit-transform: translateX(26px);
		-ms-transform: translateX(26px);
		transform: translateX(26px);
	}

	/* Rounded sliders */
	.slider.round {
		border-radius: 34px;
	}

	.slider.round:before {
		border-radius: 50%;
	}
</style>
