# Deployment Routines

Deployments are handled both automatically, or manually if needed, via GitHub Actions, and follow a pattern of expected secrets and/or inputs.

The various deployment steps rely on various secrets by default (these can be modified in your project, but these are the defaults). In addition, the following secrets are used across flows:

| Name                                    | Description                                                             |
|-----------------------------------------|-------------------------------------------------------------------------|
| `ACTIONS_DEPLOYMENT_KEY_ED25519_BASE64` | The private key used to connect to the remote server, in base64 format. |

## Production

Code is built, and pushed to production whenever a new release is published. By tieing the deployment to a release, we can ensure that the code is tested in a stage or development environment first, and that no code is accidentally put live by a commit or merge to the wrong branch.

It is also possible to run the workflow named `Manually deploy to production` to push a self-determined release tag, commit reference, or branch to production. For example if a hotfix is needed, or a rapid rollback.

**Any code merged to production should already have been tested on stage, and validation rules should have run against the Pull Requests adding features to stage.**

The production deployment relies on the following secrets existing:

| Name                                    | Description                                                                         |
|-----------------------------------------|-------------------------------------------------------------------------------------|
| `PROD_DEPLOYMENT_USERNAME`              | The username to deploy to the server as.                                            |
| `PROD_DEPLOYMENT_HOSTNAME`              | The hostname or IP of the server you are deploying to.                              |
| `PROD_DEPLOYMENT_PATH`                  | The path on the remote server to push files into.                                   |
| `PROD_DEPLOYMENT_HOSTKEY`			   | The host key of the remote server, generated using `ssh-keyscan`, in base64 format. |

## Stage

Code is built, and pushed to stage servers when new code is pushed to the `stage` branch.

Code pushed ot the `stage` branch should be tested as part of the Pull Request routine, as the code will be deployed to the stage servers as soon as it is merged.

It is also possible to run the workflow named `Manually deploy to stage` to push a self-determined release tag, commit reference, or branch to production. This is useful for testing a brand new feature quickly, where the feature branch can be deployed, but should be agreed upon with other team members to avoid new merges overwriting your deployment during such testing.

The stage deployment relies on the following secrets existing:

| Name                          | Description                                                                          |
|-------------------------------|--------------------------------------------------------------------------------------|
| `STAGE_DEPLOYMENT_USERNAME`   | The username to deploy to the server as.                                             |
| `STAGE_DEPLOYMENT_HOSTNAME`   | The hostname or IP of the server you are deploying to.                               |
| `STAGE_DEPLOYMENT_PATH`       | The path on the remote server to push files into.                                    |
| `STAGE_DEPLOYMENT_HOSTKEY`	 | The host key of the remote server, generated using `ssh-keyscan`, in base64 format.  |

## Sharing and re-usability

The deployment flows have some shared elements, they make use of GitHub Actions reusable workflows for things like checking out the source, and building it, so that a pre-built set
of files can be shared across various build steps.

In addition to this, the final deployment step is carried out by a shared workflow from a private repository in the case of Dekodes own projects.

The private repository allows us to maintain a single set of deployment routines that may change over time, ensuring our deployments always follow best practices, and are kept secret so that any and all additional security checks that are part of the deployment routine can remain private.
