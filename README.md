# 자기소개 + 블로그 사이트

## 개요

본 프로젝트는 개인 포트폴리오 사이트로, 자기소개(About)와 블로그 기능을 통합한 웹 애플리케이션입니다.
윈도우10 환경에서 개발하며, 실제 배포는 우분투(Ubuntu) 서버에서 운영됩니다.

## 기술 스택

* **백엔드**: PHP 8.2
* **데이터베이스**: MariaDB
* **프론트엔드**: HTML5, CSS3, JavaScript (vanilla JS)
* **서버**: Apache, Ubuntu
* **버전 관리**: Git

## 주요 기능

1. **Home / About**

   * 자기소개 및 이력 정보 표시
2. **Blog**

   * 카테고리별 글 목록 조회
   * 글 읽기(조회 수 증가)
   * 게시글 생성·수정·삭제(관리자)
3. **Sidebar**

   * 폴더(카테고리) 트리 구조 표시
   * 최상위(폴더 미할당) 글과 폴더별 글 목록 토글
4. **관리자(Admin)**

   * 게시글 관리(posts\_list.php)
   * 대표 글(featured) 토글
   * 댓글 허용(allow\_comment) 토글
5. **폴더 관리(Admin)**

   * 폴더 생성·수정·소프트 삭제·하드 삭제·복원
   * 드래그&드롭 정렬 지원

## 실행

* 개발 서버 (윈도우): `http://localhost/portfolio-blog/public/`
* 관리자 페이지: `http://localhost/portfolio-blog/admin/posts_list.php`

## 배포 (Ubuntu)

1. Ubuntu 서버에 Git 설치 및 리포지토리 클론
2. PHP, MySQL, Apache 설치
3. 데이터베이스 설정 및 마이그레이션
4. VirtualHost 설정: DocumentRoot를 `public/` 으로 지정
5. SSL 인증서 적용 (Let's Encrypt 권장)

## 라이선스

MIT License
