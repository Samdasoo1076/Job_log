<div class="board-list">
										<div class="gallery-board">
											<ul class="gallery-board-list">
												<?
												if (sizeof($arr_rs) > 0) {
													foreach ($arr_rs as $row) {
														
														$title = htmlspecialchars($row["TITLE"]);
														$thumbnail = !empty($row["FILE_NM"]) 
															? "/upload_data/board/B_1_2/" . $row["FILE_NM"] 
															: "/assets/images/content/img-media-list-ex01.jpg";
														$reg_date = date("Y.m.d", strtotime($row["REG_DATE"])); 
														$highlightedTitle = highlightKeyword($title, $s); // 검색어 강조 적용
												?>
														<li>
															<a href="view.do?b=<?= $b ?>&bn=<?= $row['B_NO'] ?>&m_type=<?= $m_type ?>&nPage=<?= $nPage ?>">
																<div class="img-wrap">
																	<img src="<?= $thumbnail ?>" alt="<?= $title ?>">
																</div>
																<div class="cont-wrap">
																	<p class="title"><?= $highlightedTitle ?></p>
																	<span class="date"><?= $reg_date ?></span>
																</div>
															</a>
														</li>
												<?
													}
												} else {
													?>
												<p>등록된 게시물이 없습니다.</p>
												<?}
												?>
											</ul>
										</div>
									</div>