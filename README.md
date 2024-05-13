# Hocares Backend


## Panduan Instalasi dan Penggunaan


### Langkah 1: Unduh dan Ekstrak Proyek

1. Unduh proyek Hocares Backend dari repositori GitHub [di sini](https://github.com/alvansoleh/HocaresBE).

2. Ekstrak file yang diunduh ke dalam folder yang diinginkan pada sistem Anda.

![image](https://github.com/alvansoleh/HocaresBE/assets/134778608/9ab65148-215e-410b-9fce-0381a3caa641)

### Langkah 2: Unduh dan Instal XAMPP Control Panel

1. Unduh XAMPP Control Panel dari situs resmi [di sini](https://www.apachefriends.org/download.html).

![image](https://github.com/alvansoleh/HocaresBE/assets/134778608/e4c8afa9-aa36-49ba-87d6-4edf3500bc1b)

2. Ikuti instruksi untuk menginstal XAMPP di folder yang Anda pilih. Sebagai contoh, di sini kita menginstal di folder D:\xampp.

![image](https://github.com/alvansoleh/HocaresBE/assets/134778608/f89f96b4-afc9-48d9-8108-e46b3b45b204)

### Langkah 3: Mulai XAMPP dan Aktifkan Modul Apache serta MySQL

1. Buka XAMPP Control Panel yang telah diinstal.

![image](https://github.com/alvansoleh/HocaresBE/assets/134778608/a939fcbb-6aed-46bf-9428-7f6820480b6d)

2. Aktifkan modul Apache dan MySQL dengan mengklik tombol "Start".

![image](https://github.com/alvansoleh/HocaresBE/assets/134778608/c3684428-b8ca-49a6-a9bc-8c14fbc2c92d)

### Langkah 4: Akses phpMyAdmin Melalui Browser

1. Buka browser Anda (contoh: Chrome) dan ketikkan localhost/phpmyadmin/ pada bar alamat. Ini akan membuka antarmuka phpMyAdmin.

![image](https://github.com/alvansoleh/HocaresBE/assets/134778608/389e0448-7745-4912-ba66-de4cbdddc9e5)

### Langkah 5: Buat Database Baru

1. Di antarmuka phpMyAdmin, pilih tab "Databases" dan buat database baru dengan nama db_sim_rs atau nama bebas lainnya sesuai preferensi Anda.

![image](https://github.com/alvansoleh/HocaresBE/assets/134778608/3c941f13-7fbb-4013-bfac-d29b8f5ef296)

### Langkah 6: Impor Database

1. Pilih tab "Import" dan pilih file database yang ada dalam proyek Hocares Backend yang telah diekstrak sebelumnya. Nama file database untuk proyek ini adalah u718878629_sim_rs.sql. Nama file database bisa berbeda saat proses pembuatannya. Klik tombol "Import" di bagian bawah halaman.

![image](https://github.com/alvansoleh/HocaresBE/assets/134778608/c056b70a-024b-4eb1-bac5-351cf1a2e3a2)

### Langkah 7: Verifikasi Database

1. Setelah berhasil diimpor, database Anda akan ditampilkan dalam daftar database di phpMyAdmin.

![image](https://github.com/alvansoleh/HocaresBE/assets/134778608/d48ac48a-7317-4037-ab57-accb5a123841)

### Langkah 8: Akses Backend

1. Buka tab baru pada browser Anda dan ketikkan localhost/HocaresBE-main. Ini akan membuka halaman utama backend Hocares.

![image](https://github.com/alvansoleh/HocaresBE/assets/134778608/53a8ebec-64fd-47b8-8c91-f9209f07611e)


selesai untuk backend kita akan lanjut ke frontend https://github.com/alvansoleh/HocaresFE
## Getting started

To make it easy for you to get started with GitLab, here's a list of recommended next steps.

Already a pro? Just edit this README.md and make it your own. Want to make it easy? [Use the template at the bottom](#editing-this-readme)!

## Add your files

- [ ] [Create](https://docs.gitlab.com/ee/user/project/repository/web_editor.html#create-a-file) or [upload](https://docs.gitlab.com/ee/user/project/repository/web_editor.html#upload-a-file) files
- [ ] [Add files using the command line](https://docs.gitlab.com/ee/gitlab-basics/add-file.html#add-a-file-using-the-command-line) or push an existing Git repository with the following command:

```
cd existing_repo
git remote add origin https://gitlab.com/chevalier-lab/minyak-jelantah/api-jelantah.git
git branch -M main
git push -uf origin main
```

## Integrate with your tools

- [ ] [Set up project integrations](https://gitlab.com/chevalier-lab/minyak-jelantah/api-jelantah/-/settings/integrations)

## Collaborate with your team

- [ ] [Invite team members and collaborators](https://docs.gitlab.com/ee/user/project/members/)
- [ ] [Create a new merge request](https://docs.gitlab.com/ee/user/project/merge_requests/creating_merge_requests.html)
- [ ] [Automatically close issues from merge requests](https://docs.gitlab.com/ee/user/project/issues/managing_issues.html#closing-issues-automatically)
- [ ] [Enable merge request approvals](https://docs.gitlab.com/ee/user/project/merge_requests/approvals/)
- [ ] [Set auto-merge](https://docs.gitlab.com/ee/user/project/merge_requests/merge_when_pipeline_succeeds.html)

## Test and Deploy

Use the built-in continuous integration in GitLab.

- [ ] [Get started with GitLab CI/CD](https://docs.gitlab.com/ee/ci/quick_start/index.html)
- [ ] [Analyze your code for known vulnerabilities with Static Application Security Testing(SAST)](https://docs.gitlab.com/ee/user/application_security/sast/)
- [ ] [Deploy to Kubernetes, Amazon EC2, or Amazon ECS using Auto Deploy](https://docs.gitlab.com/ee/topics/autodevops/requirements.html)
- [ ] [Use pull-based deployments for improved Kubernetes management](https://docs.gitlab.com/ee/user/clusters/agent/)
- [ ] [Set up protected environments](https://docs.gitlab.com/ee/ci/environments/protected_environments.html)

***

# Editing this README

When you're ready to make this README your own, just edit this file and use the handy template below (or feel free to structure it however you want - this is just a starting point!). Thank you to [makeareadme.com](https://www.makeareadme.com/) for this template.

## Suggestions for a good README
Every project is different, so consider which of these sections apply to yours. The sections used in the template are suggestions for most open source projects. Also keep in mind that while a README can be too long and detailed, too long is better than too short. If you think your README is too long, consider utilizing another form of documentation rather than cutting out information.

## Name
Choose a self-explaining name for your project.

## Description
Let people know what your project can do specifically. Provide context and add a link to any reference visitors might be unfamiliar with. A list of Features or a Background subsection can also be added here. If there are alternatives to your project, this is a good place to list differentiating factors.

## Badges
On some READMEs, you may see small images that convey metadata, such as whether or not all the tests are passing for the project. You can use Shields to add some to your README. Many services also have instructions for adding a badge.

## Visuals
Depending on what you are making, it can be a good idea to include screenshots or even a video (you'll frequently see GIFs rather than actual videos). Tools like ttygif can help, but check out Asciinema for a more sophisticated method.

## Installation
Within a particular ecosystem, there may be a common way of installing things, such as using Yarn, NuGet, or Homebrew. However, consider the possibility that whoever is reading your README is a novice and would like more guidance. Listing specific steps helps remove ambiguity and gets people to using your project as quickly as possible. If it only runs in a specific context like a particular programming language version or operating system or has dependencies that have to be installed manually, also add a Requirements subsection.

## Usage
Use examples liberally, and show the expected output if you can. It's helpful to have inline the smallest example of usage that you can demonstrate, while providing links to more sophisticated examples if they are too long to reasonably include in the README.

## Support
Tell people where they can go to for help. It can be any combination of an issue tracker, a chat room, an email address, etc.

## Roadmap
If you have ideas for releases in the future, it is a good idea to list them in the README.

## Contributing
State if you are open to contributions and what your requirements are for accepting them.

For people who want to make changes to your project, it's helpful to have some documentation on how to get started. Perhaps there is a script that they should run or some environment variables that they need to set. Make these steps explicit. These instructions could also be useful to your future self.

You can also document commands to lint the code or run tests. These steps help to ensure high code quality and reduce the likelihood that the changes inadvertently break something. Having instructions for running tests is especially helpful if it requires external setup, such as starting a Selenium server for testing in a browser.

## Authors and acknowledgment
Show your appreciation to those who have contributed to the project.

## License
For open source projects, say how it is licensed.

## Project status
If you have run out of energy or time for your project, put a note at the top of the README saying that development has slowed down or stopped completely. Someone may choose to fork your project or volunteer to step in as a maintainer or owner, allowing your project to keep going. You can also make an explicit request for maintainers.
