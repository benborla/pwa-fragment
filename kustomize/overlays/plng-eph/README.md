# EPH overlay
This overlay is for the PLNG Ephemeral environment.

This overlay does **not** need an ingress. Since webfront is running inside the cluster on EPH, we don't need to expose fragments to the outside world. Webfront can instead include the fragments via the fragments' service name.