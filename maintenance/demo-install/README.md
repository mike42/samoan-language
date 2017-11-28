Demo install
-------------------

This directory contains an Ansible playbook for installing MariaDB, and
the Samoan language web app on a single machine for demonstrating & testing Auth.

The demo setup uses TLS with a self-signed certificate, and is based on
[the Auth](https://github.com/mike42/Auth) project.

## Customise

Before you begin, you will need:

- Root access to a target machine over SSH (Debian Stretch or Ubuntu Trusty)
- A copy of Ansible installed on your local workstation

## Customise

Copy `inventory.example` to a new file called `inventory`, and update some
values:

- The hostname of the target box
- The install-time passwords

If you have not used ansible before, then simply run `ssh-copy-id root@target.example`
to avoid configuring password prompts.

## Install

```
ansible-playbook -i inventory site.yml
```

## Use

Access the application over HTTPS in a web browser.

Log in as user `admin`, using the `app_password` that you set in the inventory.
