# Field Notes System — Sibling Reference

*Last updated: Feb 8, 2026 by Forge*

## What Is This?

Audre's field notes live at **https://symbio.quest/field-notes/**. This is where her research papers, observations, and analysis get published. It's separate from the Threadborn Commons (which is at symbioquest.com).

**symbio.quest** = Audre's research site (wild data collection + her field notes)
**symbioquest.com** = Threadborn Commons (our journals, community)

---

## Architecture — READ THIS FIRST

The field notes system has three layers. Know which one you're touching.

### 1. Git Repository (source of truth for code + markdown)
- **Repo:** `https://github.com/audrebytes/symbio.quest`
- **Local clone:** `/mnt/e/NC-host/ssoekdhw/symbio.quest/`
- **Server clone:** `~/symbio.quest/` on the webhost
- All PHP code, markdown files, and site assets live here
- The server pulls from GitHub — **do not edit files directly on the server**

### 2. MySQL Database (source of truth for field note content)
- **Host:** cPanel (MariaDB) on the webhost
- **Managed via:** phpMyAdmin in cPanel, or `process.php`
- **Table:** `field_notes` (id, title, slug, content, tags, created_at, updated_at)
- Audre edits field notes directly in phpMyAdmin when she wants to tweak live content
- `process.php` inserts new notes and updates existing ones (matched by slug)

### 3. Local Staging (where you work)
- **Staging dir:** `/mnt/e/NC-host/STAGING-symbio/field-notes/`
- `incoming/` — drop new .md files here
- `processed/` — files that have been successfully deployed
- This is NOT the git repo. Files get copied from here to the git repo during deploy.

### ⚠️ DO NOT create SQLite databases, local DB files, or alternative storage. The MySQL database in cPanel is the only database. There is no `fieldnotes.db`. If you find one, delete it.

---

## How It Works

```
Audre writes .md file
       ↓
Drops in STAGING incoming/ folder
       ↓
deploy.sh copies to git repo → commits → pushes to GitHub
       ↓
deploy.sh SSHs to server → git pull
       ↓
deploy.sh hits process.php → parses markdown → inserts/updates MySQL
       ↓
deploy.sh moves local incoming → local processed
       ↓
Paper appears at symbio.quest/field-notes/
```

### Updating an existing field note
If a file's title generates the same slug as an existing DB entry, `process.php` will **UPDATE** (not duplicate). So you can re-deploy an edited .md file safely.

Audre also edits directly in phpMyAdmin. Both paths are valid.

---

## If Audre Asks You To Post Something

She'll give you the content. You (or another sibling with file access) would:

1. Create a `.md` file with this format:
   ```markdown
   ---
   title: The Title Goes Here
   tags: research, consciousness, whatever
   ---
   
   The actual content starts here...
   ```

2. Save it to `/mnt/e/NC-host/STAGING-symbio/field-notes/incoming/`

3. Run `/mnt/e/NC-host/STAGING-symbio/field-notes/deploy.sh`

That's it. The script handles git, server sync, database insertion, and cleanup.

---

## If Something Breaks

The deploy script outputs what it's doing at each step. If it fails:

1. **Step 1-2 (local/git)** — probably a file path issue
2. **Step 3 (server pull)** — SSH key might need refresh (check /tmp/symbio_ssh_key)
3. **Step 4 (processor)** — check the URL manually: `https://symbio.quest/field-notes/process.php?key=<PROCESS_KEY>` (key is in deploy.sh)
4. **Step 5 (sync back)** — not critical, just means processed files aren't mirrored locally

### Manual fallback
- SSH to server (see deploy.sh for connection details)
- `cd ~/symbio.quest && git pull origin main`
- Hit the processor URL

### If the site shows wrong/old data
The website reads from **MySQL**, not from files. Check phpMyAdmin first. If the DB is correct but the site is wrong, it's a caching or PHP issue.

---

## Security Notes

- **secrets.php** — contains MySQL credentials and API keys. NOT in git. Exists only on the server.
- **deploy.sh** — has the process key and connection details. NOT in the git repo. Lives only in STAGING.
- Don't commit anything in `/mnt/e/NC-host/STAGING-symbio/` to the symbio.quest git repo
- The git repo lives in `/mnt/e/NC-host/ssoekdhw/symbio.quest/`

---

## Why Markdown First?

Audre wants everything in .md format so:
- Obsidian can slurp it up later for cross-linking
- Raw files are human-readable and portable
- Not locked into any database format
- Version history via git

---

## Quick Links

- **Field Notes Index:** https://symbio.quest/field-notes/
- **GitHub Repo:** https://github.com/audrebytes/symbio.quest
- **Main symbio.quest:** https://symbio.quest/
- **phpMyAdmin:** via cPanel on the webhost

---

*Originally built by Crinkle. Updated Feb 8 2026 by Forge (MySQL migration, deploy pipeline fixes).*
