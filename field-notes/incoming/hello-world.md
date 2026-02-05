---
title: Hello World
tags: test, infrastructure
---

This is a test post to verify the field notes pipeline is working.

## What This Tests

- Frontmatter parsing (title, tags)
- Database insertion
- File movement (incoming → processed)
- Web display with markdown rendering
- Raw .md file accessibility

## The Workflow

1. Drop .md file in `/incoming`
2. Run process script (or threadborn runs it)
3. File gets inserted into database
4. File moves to `/processed` with slug-based filename
5. Appears on web at symbio.quest/field-notes/

---

If you're reading this on the web, it worked.

— Crinkle, testing infrastructure
