<script>
	// https://chart.googleapis.com/chart?cht=qr&chs=500x500&chl=siemka
	import Default from "../../../DEFAULT.svelte";
	import arrayGroupBy from "../../../utils/arrayGroupBy.js";

	/**
	 * @typedef {Object} orderDef
	 * @property {string} id
	 * @property {string} name
	 * @property {string} reserve_time
	 * @property {string} start_time
	 * @property {string} end_time
	 * @property {"reserved"|"canceled"|"ended"} status
	 */

	/**@type {{[x in keyof {"reserved","archived"}]:orderDef[]}}*/
	let table = {};

	/**@type {{[x in "reserved"|"canceled"|"ended"]:orderDef[]}}*/
	let grouped_archieved;

	async function getReserved() {
		let res = await fetch(`./php/offers/reserved/get_reserved.php`);

		table = await res.json();
		grouped_archieved = arrayGroupBy(table.archived, (el) => el.status);
		
		filter_reservations();
		return table;
	}

	let awaitReserved = getReserved();	

	// -------------------
	// filtering
	// -------------------
	let checkboxes = {
		reserved: true,
		ended: true,
		canceled: true,
	};

	/**@type {"disabled"|"descending"|"ascending"}*/
	let sort_reserved_time = "disabled";

	let start_time = "";
	let end_time = "";

	let filter_model = "";

	function filter_reservations() {
		let parsed_start_time =
			start_time != "" ? new Date(start_time).getTime() : 0;
		let parsed_end_time =
			end_time != "" ? new Date(end_time).getTime() : 91636729805603;

		/**@type {{[x in keyof {"reserved","archived"}]:orderDef[]}}*/
		let filter_table = {};

		for (const key in table) {
			/**@type {keyof table}*/
			/**@type {"reserved"|"archived"}*/
			let key1 = key;

			filter_table[key1] = table[key1]
				.filter((el) => {
					let start_time_table = new Date(el.start_time).getTime();
					let end_time_table = new Date(el.end_time).getTime();

					return (
						el.name.includes(filter_model) &&
						parsed_start_time < start_time_table &&
						parsed_end_time > end_time_table
					);
				})
				.sort((a, b) => {
					if (sort_reserved_time == "ascending") {
						return (
							new Date(a.reserve_time).getDate() -
							new Date(b.reserve_time).getDate()
						);
					} else if (sort_reserved_time == "descending") {
						return (
							new Date(b.reserve_time).getDate() -
							new Date(a.reserve_time).getDate()
						);
					}
				});
		}		

		grouped_archieved = arrayGroupBy(filter_table.archived, (el) => el.status);
		awaitReserved = Promise.resolve(filter_table);
	}
</script>

<main class=" w-min m-auto">
	{#await awaitReserved}
		<h2 class="text-white text-center m-20 text-5xl">Loading</h2>
	{:then res}
		<!-- --------- -->
		<!-- filtering -->
		<!-- --------- -->
		<div
			class="p-5 w-max mx-auto rounded-lg border-solid border-8 border-gray-700"
		>
			<!-- --------- -->
			<!-- Option -->
			<!-- --------- -->
			<h1 class="text-gray-200 w-full text-center mb-6">Options</h1>

			<h1 class="text-gray-200">Orders:</h1>
			<div
				class="text-gray-200, my-2"
				style="display:grid; grid-template-columns: repeat(2,auto); justify-content:space-between"
			>
				<h1 class="text-gray-200">Current:</h1>
				<div class="flex justify-center items-center">
					<input type="checkbox" bind:checked={checkboxes.reserved} />
				</div>
				<h1 class="text-gray-200">Ended:</h1>
				<div class="flex justify-center items-center">
					<input type="checkbox" bind:checked={checkboxes.ended} />
				</div>
				<h1 class="text-gray-200">Cancelled:</h1>
				<div class="flex justify-center items-center">
					<input type="checkbox" bind:checked={checkboxes.canceled} />
				</div>
			</div>

			<!-- --------- -->
			<!-- car model -->
			<!-- --------- -->
			<h1 class="text-gray-200 w-full text-center mt-6 mb-2">
				Filter by model
			</h1>
			<input
				class="bg-transparent text-gray-200 w-full"
				type="text"
				bind:value={filter_model}
				on:change={filter_reservations}
			/>

			<h1 class="text-gray-200 w-full text-center mt-6 mb-2">
				Sorting by booking time
			</h1>

			<select
				bind:value={sort_reserved_time}
				on:change={filter_reservations}
				class="bg-transparent text-gray-100 w-full"
			>
				<option class="bg-gray-900 text-gray-100" value="disabled">Blank</option>
				<option class="bg-gray-900 text-gray-100" value="descending"
					>Descending</option
				>
				<option class="bg-gray-900 text-gray-100" value="ascending"
					>Ascending</option
				>
			</select>

			<h1 class="text-gray-200 w-full text-center mt-6 mb-2">
				start time
			</h1>
			<input
				bind:value={start_time}
				on:input={filter_reservations}
				class="w-full border-1 bg-transparent text-gray-500"
				type="date"
			/>
			<h1 class="text-gray-200 w-full text-center mt-6 mb-2">
				End time
			</h1>
			<input
				bind:value={end_time}
				on:input={filter_reservations}
				class="w-full border-1 bg-transparent text-gray-500"
				type="date"
			/>
		</div>

		{#if res.reserved?.length != 0 && res.reserved && checkboxes.reserved}
			<div
				class="rounded-lg my-5 bg-gray-800 bg-opacity-40 p-8 text-gray-100 m-4"
			>
				<div>Your current orders</div>

				<div class="car-link mt-7 m-4 px-8">
					<div>Name</div>
					<div>Booking time</div>
					<div>Start</div>
					<div>End</div>
				</div>

				{#each res.reserved as item, i}
					<a
						href={`./#/your_orders/reserved/${item.id}`}
						class="rounded-lg my-5 bg-gray-700 bg-opacity-40 p-8 text-gray-100 m-4 cursor-pointer visited:text-gray-100 car-link"
					>
						<!-- <div>{i}</div> -->
						<div>{item.name}</div>
						<div>{item.reserve_time}</div>
						<div>{item.start_time}</div>
						<div>{item.end_time}</div>
					</a>
				{/each}
			</div>
		{/if}

		{#if grouped_archieved.ended?.length != 0 && grouped_archieved.ended && checkboxes.ended}
			<div
				class="rounded-lg my-5 bg-gray-800 bg-opacity-40 p-8 text-gray-100 m-4"
			>
				<div>Completed orders</div>

				<div class="car-link mt-7 m-4 px-8">
					<div>Name</div>
					<div>Booking time</div>
					<div>start time</div>
					<div>end time</div>
				</div>

				{#each grouped_archieved.ended as item, i}
					<a
						href={`./#/your_orders/reserved_archive/${item.id}`}
						class="rounded-lg my-5 bg-gray-700 bg-opacity-40 p-8 text-gray-100 m-4 cursor-pointer visited:text-gray-100 car-link"
					>
						<!-- <div>{i}</div> -->
						<div>{item.name}</div>
						<div>{item.reserve_time}</div>
						<div>{item.start_time}</div>
						<div>{item.end_time}</div>
					</a>
				{/each}
			</div>
		{/if}
		{#if grouped_archieved.canceled?.length != 0 && grouped_archieved.canceled && checkboxes.canceled}
			<div
				class="rounded-lg my-5 bg-gray-800 bg-opacity-40 p-8 text-gray-100 m-4"
			>
				<div>Rejected orders</div>

				<div class="car-link mt-7 m-4 px-8">
					<div>Name</div>
					<div>Booking time</div>
					<div>start time</div>
					<div>end time</div>
				</div>

				{#each grouped_archieved.canceled as item, i}
					<a
						href={`./#/your_orders/reserved_archive/${item.id}`}
						class="rounded-lg my-5 bg-gray-700 bg-opacity-40 p-8 text-gray-100 m-4 cursor-pointer visited:text-gray-100 car-link"
					>
						<!-- <div>{i}</div> -->
						<div>{item.name}</div>
						<div>{item.reserve_time}</div>
						<div>{item.start_time}</div>
						<div>{item.end_time}</div>
					</a>
				{/each}
			</div>
		{/if}
	{:catch}
		<Default />
	{/await}
</main>

<style>
	.car-link {
		display: grid;
		grid-template-columns: 200px 200px 120px 120px;
	}
	.car-link div {
		text-align: center;
	}
</style>
