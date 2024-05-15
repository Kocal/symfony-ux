<script>
    import { fade } from "svelte/transition"

    // biome-ignore lint/style/useConst: This property is mutable
    export let name = "without props";
</script>

<div transition:fade|global={{ duration: 100 }}>
    <div>Hello {name}</div>
</div>