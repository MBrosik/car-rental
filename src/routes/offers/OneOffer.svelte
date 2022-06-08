<script>
	export let model_id;
	/** @type {string}*/
	export let model;
	/** @type {string}*/
	export let img_src;
	/** @type {number}*/
	export let price_per_day;
	/**@type {"open"|"waiting"}*/
	export let status;
	/** @type {number}*/
	export let count;

	export let are_you_waiting;

	export let start_time;
	export let end_time;

	export let date_now;
	export let end_date_now;

	/**@type {()=>void}*/
	export let refresh;

	let calculated_cost = (0).toFixed(2);

	// -----------------
	// get time
	// -----------------
	function count_time_handler() {				

		if (start_time != "" && end_time != "") {
			let parsed_start = new Date(start_time).getTime();
			let parsed_end = new Date(end_time).getTime();

			calculated_cost = (
				((parsed_end - parsed_start) / (24 * 60 * 60 * 1000)) *
				price_per_day
			).toFixed(2);
		} else {
			calculated_cost = (0).toFixed(2);
		}
	}
	count_time_handler();

	function start_time_handler() {
		if (new Date(start_time).getTime()+ (24 * 60 * 60 * 1000) > new Date(end_time).getTime()) {			
			let end_time1 = new Date(new Date(start_time).getTime() + (24 * 60 * 60 * 1000));			
			end_time = `${end_time1.getFullYear()}-${(end_time1.getMonth()+1).toString().padStart(2,"0")}-${end_time1.getDate().toString().padStart(2,"0")}`			
		}

		count_time_handler();
	}
	function end_time_handler() {
		if (new Date(start_time).getTime()+ (24 * 60 * 60 * 1000) > new Date(end_time).getTime()) {			
			let start_time1 = new Date(new Date(end_time).getTime() - (24 * 60 * 60 * 1000));
			start_time = `${start_time1.getFullYear()}-${(start_time1.getMonth()+1).toString().padStart(2,"0")}-${start_time1.getDate().toString().padStart(2,"0")}`				
		}
		count_time_handler();
	}

	// -----------------
	// submit time
	// -----------------
	async function submit_button() {
		let form = new FormData();
		form.set("id", model_id);
		form.set("start_time", start_time);
		form.set("end_time", end_time);

		let res = await fetch("./php/offers/submit_offer.php", {
			method: "POST",
			body: form,
		});

		let data = await res.json();

		alert(data.code);

		refresh();
	}

	// ---------------
	// delete
	// ---------------

	async function reject_handler() {
		let form = new FormData();
		form.set("id", model_id);

		let res = await fetch("./php/offers/reject_offer.php", {
			method: "POST",
			body: form,
		});

		let data = await res.json();

		alert(data.code);
		refresh();
	}
</script>

<div class="p-4 w-96">
	<div
		class="
      h-full 
      bg-gray-800 
      bg-opacity-40 
      px-8 
      pt-16 
      pb-24 
      rounded-lg 
      overflow-hidden 
      text-center 
      relative
      flex
      flex-col
      items-center
      
      "
	>
		<img
			src={img_src}
			alt="Ni mo"
			class=" 
             h-52 
            rounded-lg 
            border-solid 
            border-8 
            border-gray-700
         "
		/>

		<h2
			class="
            tracking-widest 
            text-base
             w-2/3
            h-14             
            title-font 
            font-medium 
            text-gray-500 
            mb-1
            my-2
         "
		>
			{model}
		</h2>

		{#if status == "open"}
			<h2 class="text-green-400 w-2/3 ">Brak oczekiwań</h2>
		{:else if status == "waiting"}
			<h2>
				<h2 class="text-yellow-600">Parę osób oczekuje</h2>
			</h2>
		{/if}

		<span class="w-2/3 flex items-center  text-gray-500">
			<!-- -translate-x-10 -->
			<svg
				class="w-4 h-full mx-1 "
				stroke="currentColor"
				stroke-width="2"
				fill="none"
				stroke-linecap="round"
				stroke-linejoin="round"
				viewBox="0 0 24 24"
			>
				<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
				<circle cx="12" cy="12" r="3" />
			</svg>
			<span
				>{count}
				{#if are_you_waiting}- w tym również ty{/if}</span
			>
		</span>
		<h2
			class="
            text-gray-500 
            text-left
            text-xs 
            my-2
            w-2/3
         "
		>
			{price_per_day.toFixed(2)} zł/dzień
		</h2>

		<!-- --------------- -->
		<!-- Dates -->
		<!-- --------------- -->

		<span class="w-2/3 text-left text-gray-500 text-xs my-2">
			Data początkowa:
		</span>
		<input
			bind:value={start_time}
			on:input={start_time_handler}
			class="w-2/3 border-1 bg-transparent text-gray-500"
			type="date"
			min={date_now}
			name=""
			id="start-time"
		/>

		<span class="w-2/3 text-left text-gray-500 text-xs my-2">
			Data końcowa:
		</span>
		<input
			bind:value={end_time}
			on:input={end_time_handler}
			min={end_date_now}
			class="w-2/3 border-1 bg-transparent text-gray-500"
			type="date"
			name=""
			id="start-time"
		/>

		<!-- --------------- -->
		<!-- cost -->
		<!-- --------------- -->

		<span class="w-2/3 text-left text-red-600 text-lg my-2"
			>Koszt: {calculated_cost} zł</span
		>

		<!-- --------------- -->
		<!-- send button -->
		<!-- --------------- -->

		<button
			class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg"
			on:click={submit_button}>Zatwierdź</button
		>

		{#if are_you_waiting}
			<button
				class="text-white bg-red-500 border-0 py-2 px-8 my-2 focus:outline-none hover:bg-red-600 rounded text-lg"
				on:click={reject_handler}>Usuń</button
			>
		{/if}
	</div>
</div>
