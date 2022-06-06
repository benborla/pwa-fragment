## Configuration base

This folder contains the base configuration for the
fragment. In practice, it mostly contains the import 
of the base config and the settings for which docker
image to apply.

You can add default environment variables to the
deployment should you want to, by editing the 
patch-deployment-env-vars.yaml file. If you do,
remember to override the values as necessary in 
the different overlays.
