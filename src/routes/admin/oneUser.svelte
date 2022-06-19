<script>
	// ---------------
	// props
	// ---------------
	export let index;
	/**@type {import("./userType").UsersData}*/
	export let userData;

	async function submit() {
		let form = new FormData();
		form.append("id", userData.id);
		form.append("user_type", userData.user_type);
		form.append("activated", userData.activated);

		let res = await fetch("./php/admin/submitUser.php", {
			method: "POST",
			body: form,
		});
		
		window.location.reload();
	}
</script>

<div
	class="rounded-lg my-5 bg-gray-700 bg-opacity-40 p-8 text-gray-100 m-4 text-sm description"
>
	<div>{index}</div>
	<div>{userData.login}</div>
	<div>
		<select
			class="bg-transparent"
			bind:value={userData.user_type}
			disabled={userData.user_type == "admin"}
		>
			{#if userData.user_type == "admin"}
				<option class="bg-gray-800" value="admin">admin</option>
			{/if}			

			<option class="bg-gray-800" value="moderator">moderator</option>
			<option class="bg-gray-800" value="user">user</option>
		</select>
	</div>	
	<div>
		<select
			class="bg-transparent"
			bind:value={userData.activated}
			disabled={userData.user_type == "admin"}
		>
			<option class="bg-gray-800" value="0">0</option>
			<option class="bg-gray-800" value="1">1</option>
		</select>
	</div>
	{#if userData.user_type != "admin"}
		<button
			class="text-white bg-green-500 w-min border-0 py-2 px-8 my-2 focus:outline-none hover:bg-green-600 rounded text-sm"
			on:click={submit}
		>
			Submit
		</button>
	{/if}
</div>

<style>
	.description {
		display: grid;
		grid-template-columns: repeat(5, 1fr);
		gap: 8px;
		align-items: center;
	}
	.description div {
		display: flex;
		justify-content: center;
		align-items: center;
	}
</style>
