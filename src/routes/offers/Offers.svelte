<script>
	import OneOffer from "./OneOffer.svelte";
	import arrayGroupBy from "../../utils/arrayGroupBy.js";
	import Default from "../../DEFAULT.svelte";

	/**
	 * @typedef {Object} carDef
	 * @property {String} id
	 * @property {String} name
	 * @property {String} img
	 * @property {Number} price_per_day
	 * @property {"open"|"waiting"} status
	 * @property {Number} count
	 * @property {boolean} are_you_waiting
	 * @property {{start_time:string, end_time:string}} your_order
	 */

	/**@type {carDef[]}*/
	let main_data = [];

	/**@type {["open" | "waiting", carDef[]][]}*/
	let prepared_data;

	/**@type {number}*/
	let code;

	/**@type {number}*/
	let date_now;

	let parsed_date_now= ""
	let parsed_end_date_now= ""

	const statusEnum = {
		open: "Otwarte",
		waiting: "W trakcie oczekiwania",
	};

	async function getOffers() {
		let res = await fetch(`./php/offers/get_offers.php`);

		/**@type {{code:number, offers_filtered:carDef[], date_now:string}}*/
		let data = await res.json();

		console.log(data);
		main_data = data.offers_filtered;
		code = data.code;
		date_now =  parseInt(data.date_now)

		let nice_date = new Date(date_now);
		let nice_end_date = new Date(date_now+ (24 * 60 * 60 * 1000))

		parsed_date_now = `${nice_date.getFullYear()}-${(nice_date.getMonth()+1).toString().padStart(2,"0")}-${nice_date.getDate().toString().padStart(2,"0")}`
		parsed_end_date_now = `${nice_end_date.getFullYear()}-${(nice_end_date.getMonth()+1).toString().padStart(2,"0")}-${nice_end_date.getDate().toString().padStart(2,"0")}`		

		filter_waiting();

		return prepared_data;
	}
	
	/**@type {Promise<["open" | "waiting", carDef[]][]>}*/		
	let offers = getOffers();

	function refresh() {
		offers = getOffers();
	}

	// ----------------------------------------------------------------
	// ----------------------------------------------------------------
	// ----------------------------------------------------------------

	// ---------------------
	// filtering / sorting
	// ---------------------
	/**@type {"all"|"waiting"|"your_waiting"|"open"}*/
	let filter_waiting_bind = "all";
	let filter_model = "";
	/**@type {"disabled"|"descending"|"ascending"}*/
	let sort_price = "disabled";

	function filter_waiting() {
		let filtered = main_data.filter((el) => {
			/**@type {boolean}*/
			let bool;
			if (filter_waiting_bind == "all") bool = true;
			else if (filter_waiting_bind == "open") bool = el.status == "open";
			else if (filter_waiting_bind == "waiting") bool = el.status == "waiting";
			else if (filter_waiting_bind == "your_waiting")
				bool = el.status == "waiting" && el.are_you_waiting == true;

			// console.log(el);
			return bool && el.name.toLowerCase().includes(filter_model.toLowerCase());
		});

		let sorted = filtered;

		if (sort_price == "ascending")
			sorted = filtered.sort((a, b) => a.price_per_day - b.price_per_day);
		else if (sort_price == "descending")
			sorted = filtered.sort((a, b) => b.price_per_day - a.price_per_day);

		prepared_data = Object.entries(arrayGroupBy(sorted, (el) => el.status));

		offers = Promise.resolve(prepared_data)
		// offers = Promise.resolve(
		// 	Object.entries(arrayGroupBy(sorted, (el) => el.status))
		// );
	}
</script>

<main>
	{#await offers}
		<h1 class="text-white text-center m-20 text-5xl">Ładuję oferty</h1>
	{:then res}
		{#if code == -1}
			<h1 class="text-red-600 text-center m-20 text-5xl">
				Przetrzymujesz jedno auto!
			</h1>
		{:else}
			<!-- flex items-center flex-col gap-3 -->

			<!-- --------- -->
			<!-- filtering -->
			<!-- --------- -->
			<div
				class="p-5 w-min mx-auto rounded-lg border-solid border-8 border-gray-700"
			>
				<!-- --------- -->
				<!-- Option -->
				<!-- --------- -->
				<h1 class="text-gray-200 w-full text-center mb-6">Opcje</h1>
				<select
					bind:value={filter_waiting_bind}
					on:change={filter_waiting}
					class="bg-transparent text-gray-100"
				>
					<option class="bg-gray-900 text-gray-100" value="all"
						>Wszystkie</option
					>
					<option class="bg-gray-900 text-gray-100" value="open">Otwarte</option
					>
					<option class="bg-gray-900 text-gray-100" value="waiting"
						>Oczekujące</option
					>
					<option class="bg-gray-900 text-gray-100" value="your_waiting"
						>Oczekujące przez Ciebie</option
					>
				</select>
				<!-- --------- -->
				<!-- car model -->
				<!-- --------- -->
				<h1 class="text-gray-200 w-full text-center my-6">Nazwa modelu</h1>
				<input
					class="bg-transparent text-gray-200 w-full"
					type="text"
					bind:value={filter_model}
					on:change={filter_waiting}
				/>

				<!-- ------------- -->
				<!-- price sorting -->
				<!-- ------------- -->
				<h1 class="text-gray-200 w-full text-center my-6">
					Sortowanie po cenie
				</h1>
				<select
					bind:value={sort_price}
					on:change={filter_waiting}
					class="bg-transparent text-gray-100 w-full"
				>
					<option class="bg-gray-900 text-gray-100" value="disabled"
						>Brak</option
					>
					<option class="bg-gray-900 text-gray-100" value="descending"
						>Malejąco</option
					>
					<option class="bg-gray-900 text-gray-100" value="ascending"
						>Rosnąco</option
					>
				</select>
			</div>

			<div class="flex flex-col max-w-4xl m-auto">
				{#each res as item1, i}
					<div
						class="rounded-lg my-5 bg-gray-800 bg-opacity-40 p-8 text-gray-100"
					>
						{statusEnum[item1[0]]}
					</div>

					<div class="flex flex-wrap justify-between max-w-4xl m-auto">
						{#each item1[1] as item, i}
							<OneOffer
								model_id={item.id}
								model={item.name}
								img_src={item.img}
								price_per_day={item.price_per_day}
								status={item.status}
								count={item.count}
								are_you_waiting={item.are_you_waiting}
								start_time={item.your_order.start_time}
								end_time={item.your_order.end_time}
								{refresh}
								date_now = {parsed_date_now}
								end_date_now = {parsed_end_date_now}
							/>
						{/each}
					</div>
				{/each}
			</div>
		{/if}
	{:catch}
		<Default />
	{/await}
</main>

<style>
</style>
