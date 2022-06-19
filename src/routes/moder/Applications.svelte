<script>
	import Default from "../../DEFAULT.svelte";

	import ApplicationComp from "./ApplicationComp.svelte";

	/**@typedef {import("./type.js").application} application*/

	async function get_application() {
		let res = await fetch("./php/moder/get_applications.php");

		/**
		 * @typedef {Object} resDataType
		 * @property {{ [x in "waiting"|"reserved"|"canceled"|"ended"]: { [y:number]:application[] }} } byStatus 1 - status, 2 - carid
		 * @property {{ [x:number]: { [y in "waiting"|"reserved"|"canceled"|"ended"]:application[] }} } byUser 1 - userID, 2 - status
		 */

		/**@type {{[x in "reserved"|"archived"]: resDataType}}*/
		let data = await res.json();

		return data;
	}
	let applications = get_application();

	// ------------------
	// checkbox
	// ------------------

	let checked = true;

	let checkboxes = {
		reserved: true,
		archived: true,
	};

	const mainTitleEnum = {
		archived: "archived",
		reserved: "reserved",
	};
</script>

<main class="w-max m-auto">
	{#await applications}
		<h2 class="text-white text-center m-20 text-5xl">Loading...</h2>
	{:then res}
		<div
			class="p-5 w-max mx-auto rounded-lg border-solid border-8 border-gray-700"
		>
			<!-- --------- -->
			<!-- Option -->
			<!-- --------- -->
			<h1 class="text-gray-200 w-full text-center mb-6">Options</h1>

			<h1 class="text-gray-200 text-center">Grouping:</h1>

			<!-- ------ -->
			<!-- switch -->
			<!-- ------ -->
			<div class="flex items-center justify-center my-3">
				<label class="switch">
					<input type="checkbox" bind:checked />
					<span class="slider round" />
				</label>
			</div>

			<div
				class="text-gray-200, my-2 gap-3"
				style="display:grid; grid-template-columns: repeat(2,auto); justify-content:space-between"
			>
				<h1 class="text-gray-200">Current:</h1>
				<div class="flex justify-center items-center">
					<input type="checkbox" bind:checked={checkboxes.reserved} />
				</div>
				<h1 class="text-gray-200">Archived:</h1>
				<div class="flex justify-center items-center">
					<input type="checkbox" bind:checked={checkboxes.archived} />
				</div>
			</div>
		</div>

		{#each Object.entries(res) as itemRes, i}
			{#if checkboxes[itemRes[0]]}
				<div
					class="rounded-lg my-5 bg-gray-700 bg-opacity-20 p-8 text-gray-100"
				>
					<h1>{mainTitleEnum[itemRes[0]]}</h1>
					{#if checked}
						<!-- --------- -->
						<!-- by Status -->
						<!-- --------- -->
						{#if itemRes[0] == "reserved"}
							<!-- --------- -->
							<!-- reserved -->
							<!-- --------- -->
							{#if itemRes[1].byStatus.waiting}
								<ApplicationComp
									obj_element={itemRes[1].byStatus.waiting}
									title="Waiting"
									reservedBool={false}
									sortType="byStatus"
								/>
							{/if}
							{#if itemRes[1].byStatus.reserved}
								<ApplicationComp
									obj_element={itemRes[1].byStatus.reserved}
									title="Reserved"
									reservedBool={true}
									sortType="byStatus"
								/>
							{/if}
						{:else}
							<!-- --------- -->
							<!-- archived -->
							<!-- --------- -->
							{#if itemRes[1].byStatus.ended}
								<ApplicationComp
									obj_element={itemRes[1].byStatus.ended}
									title="Ended"
									reservedBool={false}
									sortType="byStatus"
								/>
							{/if}
							{#if itemRes[1].byStatus.canceled}
								<ApplicationComp
									obj_element={itemRes[1].byStatus.canceled}
									title="Cancelled"
									reservedBool={false}
									sortType="byStatus"
								/>
							{/if}
						{/if}
					{:else}
						<!-- ------- -->
						<!-- by User -->
						<!-- ------- -->
						{#each Object.entries(itemRes[1].byUser) as item, i}
							{#if itemRes[0] == "reserved"}
								<ApplicationComp
									obj_element1={item[1]}
									title={item[1].reserved
										? item[1].reserved[0].login
										: item[1].waiting[0].login}
									reservedBool={item[1].reserved == true}
									sortType="byUser"
								/>
							{:else}
								<ApplicationComp
									obj_element1={item[1]}
									title={item[1].canceled
										? item[1].canceled[0].login
										: item[1].ended[0].login}
									reservedBool={false}
									sortType="byUser"
								/>
							{/if}
						{/each}
					{/if}
				</div>
			{/if}
		{/each}
	{:catch}
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
