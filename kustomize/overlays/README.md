## Overlays

This folder contains sub-folders for each unique "overlay"
that we want to apply on top of the base configuration.

An overlay adds or modifies the base so that it will work
properly in the environment that the overlay is for 
(e.g., local, qa-mt1 and so on). It is possible
to add or modify existing resources. Adding can be 
done by adding the new resources under the `resources:` 
node in the kustomization.yaml file. Modifications can
be done by adding the modifyer file under the 
`patchesStrategicMerge:` node in the kustomization.yaml file.
It is **not** possible to remove resources from a base in an overlay. 

To modify (patch) a resource, you need to ensure that your patch
file has the same `apiVersion`, `kind` and `metadata.name` as the
resource you intend to patch, and you need to ensure the patch
uses the same path and names.

Say for example you wish to patch the image of a deployment. The
patch file would have to look like this:
```yaml
apiVersion: apps/v1                     # same apiVersion
kind: Deployment                        # same kind
metadata:
  name: fragment                        # same metadata.name
spec:
  template:
    spec:
      containers:                       # same path
        - name: CONTAINER-NAME-HERE     # same name
          # Whatever you wish to patch
          # from the base goes here
```